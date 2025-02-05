<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Inventory;
use Exception;
use App\Exceptions\MoneyException;
use App\Exceptions\PointException;


class SaleController extends Controller
{
    /**
     * Configから取得した通貨の情報を基に$currencyを返却
     * @return array|mixed|string|\Illuminate\Config\Repository|null
     */
    protected function currency()
    {
        $currency = config('app.currency');
        return match ($currency) {
            'JPY' => __('sale.yen'),
            'USD' => __('sale.dollars'),
            default => $currency,
        };
    }

    /**
     * indexビューを表示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $sales = [];
        $currency = $this->currency();

        return view('sale.index', compact('sales', 'currency'));
    }

    /**
     * 購入する商品と数量を保存
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\RedirectResponse
     */
    public function buy(Request $request)
    {
        $request->validate([
            'product_code' => 'required',
            'quantity' => 'required|integer|min:0',
        ]);

        $isProduct = Product::where('product_code', $request->product_code)->exists();
        if ($isProduct) {
            $product = Product::where('product_code', $request->product_code)->first();
            $productName = $product->product_name;
            $price = $product->price;
            $priceInTax = $price * (1 + $request->tax_rate / 100);
            $quantity = $request->quantity;

            $sales = $request->session()->get('sales', []);

            $sale = [
                'product_name' => $productName,
                'quantity' => $quantity,
                'price' => $priceInTax,
                'subTotal' => $quantity * $priceInTax,
            ];
            $sales[] = $sale;

            $request->session()->put('sales', $sales);

            return redirect()->route('sale.index');
        } else {
            return redirect()->route('sale.index')->with('error', __('error.productNotFound'));
        }
    }

    /**
     * 顧客情報を取得
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\RedirectResponse
     */
    public function customer(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|integer',
        ]);

        $point = $request->session()->get('point');

        $customerId = $request->customer_id;

        if (!empty($customerId) && is_null($point)) {
            $customer = Customer::find($customerId);

            if ($customer) {
                $point = $customer->point;
                $request->session()->put('point', $point);
            } else {
                return redirect()->route('sale.index')->with('error', __('error.customerNotFound'));
            }
        } else {
            return redirect()->route('sale.index')->with('error', __('error.occurred'));
        }

        $request->session()->put('customerId', $customerId);

        return redirect()->route('sale.index');
    }

    /**
     * 購入リストを削除
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request)
    {
        $request->session()->forget('point');
        $request->session()->forget('sales');

        return redirect()->route('sale.index');
    }

    /**
     * 購入をキャンセル
     * @param \Illuminate\Http\Request $request
     * @param mixed $index
     * @return mixed|\Illuminate\Http\RedirectResponse
     */
    public function cancel(Request $request, $index)
    {
        $sales = $request->session()->get('sales', []);

        if (isset($sales[$index])) {
            unset($sales[$index]);
        }

        $request->session()->put('sales', $sales);

        return redirect()->route('sale.index');
    }

    /**
     * 支払いを実行
     * @param \Illuminate\Http\Request $request
     * @throws \Exception
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function pay(Request $request)
    {
        try {
            $subtotal = $request->total;
            $receivedMoney = $request->receivedMoney;
            $charge = $receivedMoney - $subtotal;
            $saleItems = [];
            $rewardPoint = 0;
            $usePoint = $request->point;
            $taxRate = $request->tax_rate / 100;
            $point = $request->session()->get('point');

            DB::transaction(function () use (
                $subtotal,
                $request,
                $point,
                $usePoint,
                &$sale,
                &$saleItems,
                &$charge,
                &$rewardPoint,
                &$pointBalance,
            ) {
                $sale = Sale::create([
                    'date' => now('UTC'),
                    'subtotal' => $subtotal,
                ]);

                $saleId = $sale->id;

                $sales = $request->session()->get('sales', []);

                foreach ($sales as $saleItem) {
                    $product = Product::where('product_name', $saleItem['product_name'])->first();
                    $productId = $product->id;

                    $saleItem = SaleItem::create([
                        'sale_id' => $saleId,
                        'product_id' => $productId,
                        'quantity' => $saleItem['quantity'],
                    ]);

                    $iQuantity = Inventory::where('product_id', $productId)
                        ->first()
                        ->quantity;

                    $iQuantity -= $saleItem['quantity'];

                    Inventory::where('product_id', $productId)->update([
                        'quantity' => $iQuantity,
                    ]);

                    $saleItems[] = $saleItem;
                }

                $customerId = $request->session()->get('customerId');
                // $pointがnullでなく$usePointのほうが大きいとき
                if (!is_null($point) && $point >= $usePoint) {
                    Customer::find($customerId)->update([
                        'point' => $point - $usePoint,
                    ]);

                    $charge -= $usePoint;
                    // $usePointのほうが小さいとき例外をスロー
                } elseif ($point < $usePoint) {
                    throw new PointException();
                }

                if ($charge < 0) {
                    throw new MoneyException();
                }

                $customer = Customer::find($customerId);

                if ($customer) {
                    $rewardPoint = floor($subtotal / 100);
                    $pointBalance = $customer->point + $rewardPoint;
                    $customer->update(['point' => $pointBalance]);
                }

                $request->session()->forget('point');
                $request->session()->forget('sales');
            });

            $currency = $this->currency();

            return view('sale.paid', compact(
                'sale',
                'rewardPoint',
                'taxRate',
                'receivedMoney',
                'charge',
                'usePoint',
                'saleItems',
                'pointBalance',
                'currency'
            ));
        } catch (PointException $e) {
            DB::rollBack();
            return redirect()->route('sale.index')->with('error', __('error.point'));
        } catch (MoneyException $e) {
            DB::rollBack();
            return redirect()->route('sale.index')->with('error', __('error.money'));
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('sale.index')->with('error', __('error.occurred'));
        }
    }
}
