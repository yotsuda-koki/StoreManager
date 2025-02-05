<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * indexビューを表示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $customers = Customer::paginate(10);
        return view('customer.index', compact('customers'));
    }

    /**
     * 検索クエリからCustomerテーブルのレコードを取得してindexビューを表示
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        // クエリに基づいて顧客情報を取得
        $customers = Customer::where('id', 'LIKE', "%$query%")
            ->orWhere('customer_name', 'LIKE', "%$query%")
            ->paginate(10);

        return view('customer.index', compact('customers', 'query'));
    }

    /**
     * editビューを表示
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Request $request)
    {
        $customer = Customer::find($request->id);
        return view('customer.edit', compact('customer'));
    }

    /**
     * 顧客情報を変更
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|max:255',
            'age' => 'required|integer|min:12|max:120',
            'customer_email' => 'required|max:255',
            'point' => 'required|integer|min:0',
        ]);

        try {
            DB::transaction(function () use ($request) {
                Customer::find($request->id)->update([
                    'customer_name' => $request->customer_name,
                    'age' => $request->age,
                    'customer_email' => $request->customer_email,
                    'point' => $request->point,
                ]);
            });

            return redirect()->route('customer.index')->with('msg', __('message.edit'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('customer.index')->with('error', __('error.occurred'));
        }
    }

    /**
     * 顧客を削除
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                Customer::find($request->id)->delete();
            });

            return redirect()->route('customer.index')->with('msg', __('message.delete'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('customer.index')->with('error', __('error.occurred'));
        }
    }

    /**
     * createビューを表示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('customer.create');
    }

    /**
     * 顧客を追加
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|max:255',
            'age' => 'required|integer|min:12|max:120',
            'customer_email' => 'required|max:255',
        ]);

        try {
            DB::transaction(function () use ($request) {
                Customer::create([
                    'customer_name' => $request->customer_name,
                    'age' => $request->age,
                    'customer_email' => $request->customer_email,
                    'point' => 0,
                ]);
            });

            return redirect()->route('customer.index')->with('msg', __('message.add'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('customer.index')->with('error', __('error.occurred'));
        }
    }
}
