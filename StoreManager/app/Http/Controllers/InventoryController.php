<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * indexビューを表示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $products = Product::paginate(10);
        return view('inventory.index', compact('products'));
    }

    /**
     * 検索クエリに基づいてProductテーブルを検索
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function search(Request $request)
    {
        $query = $request->input('query');

        $products = Product::where('product_name', 'LIKE', "%$query%")
            ->orWhere('product_code', 'LIKE', "%$query%")
            ->paginate(10);

        return view('inventory.index', compact('products', 'query'));
    }

    /**
     * editビューを表示
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Request $request)
    {
        $product = Product::find($request->id);
        $inventory = Inventory::where('product_id', $request->id)->first();
        return view('inventory.edit', compact('product', 'inventory'));
    }

    /**
     * 在庫情報を変更
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer',
        ]);

        try {
            DB::transaction(function () use ($request) {
                Inventory::where('product_id', $request->id)->update([
                    'quantity' => $request->quantity,
                ]);
            });

            return redirect()->route('inventory.index')->with('msg', __('message.edit'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('inventory.index')->with('error', __('error.occurred'));
        }
    }

    /**
     * createビューを表示
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(Request $request)
    {
        $product = Product::find($request->id);
        return view('inventory.create', compact('product'));
    }

    /**
     *　発注を実行
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'quantity' => 'required|numeric',
            ]);

            $product = Product::find($request->id);

            DB::transaction(function () use ($request, $product) {
                $order = new Order();
                $order->quantity = $request->quantity;
                $order->is_receive = 0;
                $product->orders()->save($order);
            });
            return redirect()->route('inventory.index')->with('msg', __('message.order'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('inventory.index')->with('error', __('error.occurred'));
        }
    }

    /**
     * planビューを表示
     * is_receiveが0の時未受領、1の時受領済み
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function plan()
    {
        $orders = Order::where('is_receive', 0)->paginate(10);
        return view('inventory.plan', compact('orders'));
    }

    /**
     * 発注をキャンセル
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        $order = Order::find($request->id);

        if (!$order->isCancelable()) {
            return redirect()->route('order.plan')->with('error', __('error.cannotCancel'));
        }

        try {
            DB::transaction(function () use ($request) {
                Order::find($request->id)->delete();
            });

            return redirect()->route('order.plan')->with('msg', __('message.cancel'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('order.plan')->with('error', __('error.occurred'));
        }
    }

    /**
     * receiveビューを表示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function receive()
    {
        $products = Product::all();
        return view('inventory.receive', compact('products'));
    }

    /**
     * 発注を受領
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function received(Request $request)
    {
        $request->validate(['quantity' => 'required|integer|min:0']);

        try {
            $productId = $request->productId;
            $order = Order::where('product_id', $productId)
                ->where('quantity', $request->quantity)
                ->where('is_receive', 0)
                ->exists();

            if (!$order) {
                throw new \Exception();
            }

            $order = Order::where('product_id', $productId)
                ->where('quantity', $request->quantity)
                ->where('is_receive', 0)
                ->first();

            DB::transaction(function () use ($order, $productId, $request) {
                //is_receiveを受領済みの状態にする
                $order->update([
                    'is_receive' => 1,
                ]);

                $inventory = Inventory::where('product_id', $productId)->first();

                $sum = $inventory->quantity + $request->quantity;

                Inventory::where('product_id', $productId)->update([
                    'quantity' => $sum,
                ]);
            });

            return redirect()->route('order.plan')->with('msg', __('message.receive'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('order.plan')->with('error', __('error.receive'));
        }
    }
}
