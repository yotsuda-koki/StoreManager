<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
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
        $products = Product::paginate(10);
        $currency = $this->currency();
        return view('product.index', compact('products', 'currency'));
    }

    /**
     * 検索クエリに基づいてindexビューを表示
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function search(Request $request)
    {
        $query = $request->input('query');

        $products = Product::where('product_name', 'LIKE', "%$query%")
            ->orWhere('product_code', 'LIKE', "%$query%")
            ->paginate(10);

        return view('product.index', compact('products', 'query'));
    }

    /**
     * createビューを表示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('product.create');
    }

    /**
     * 商品を追加
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_code' => 'required|max:255',
            'product_name' => 'required|max:255',
            'price' => 'required|numeric',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $product = new Product([
                    'product_code' => $request->product_code,
                    'product_name' => $request->product_name,
                    'price' => $request->price,
                ]);
                $product->save();

                $inventory = new Inventory(['quantity' => 0]);
                $product->inventory()->save($inventory);
            });

            return redirect()->route('product.index')->with('msg', __('message.add'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('product.index')->with('error', __('error.occurred'));
        }
    }

    /**
     * editビューを表示
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Request $request)
    {
        $product = Product::where('id', $request->id)->first();
        return view('product.edit', compact('product'));
    }

    /**
     * 商品情報の変更
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        try {
            $request->validate([
                'product_code' => 'required|max:255',
                'product_name' => 'required|max:255',
                'price' => 'required|numeric',
            ]);

            DB::transaction(function () use ($request) {
                Product::find($request->id)->update([
                    'product_code' => $request->product_code,
                    'product_name' => $request->product_name,
                    'price' => $request->price,
                ]);
            });

            return redirect()->route('product.index')->with('msg', __('message.edit'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('product.index')->with('error', __('error.occurred'));
        }
    }

    /**
     * 商品を削除
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                Product::find($request->id)->delete();
            });
            return redirect()->route('product.index')->with('msg', __('message.delete'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('product.index')->with('error', __('error.occurred'));
        }
    }
}
