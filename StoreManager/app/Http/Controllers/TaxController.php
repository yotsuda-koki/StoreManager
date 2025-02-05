<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tax;
use Illuminate\Support\Facades\DB;

class TaxController extends Controller
{
    /**
     * indexビューを表示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $taxes = Tax::all();
        return view('tax.index', compact('taxes'));
    }

    /**
     * 税率変更予定を設定
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\RedirectResponse
     */
    public function changeTax(Request $request)
    {
        $request->validate([
            'rate' => 'required|numeric|min:0',
            'effective_from' => 'required|date',
        ]);

        try {
            DB::transaction(function () use ($request) {
                Tax::create([
                    'tax_rate' =>  $request->rate,
                    'effective_date' => $request->effective_from,
                ]);
            });

            return redirect()->route('tax.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('tax.index')->with('error', 'error.occurred');
        }
    }

    /**
     * editビューの表示
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Request $request)
    {
        $tax = Tax::find($request->id);
        return view('tax.edit', compact('tax'));
    }

    /**
     * 予定税率の変更
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'tax_rate' => 'required|numeric',
        ]);

        try {
            DB::transaction(function () use ($request) {
                Tax::find($request->id)->update([
                    'tax_rate' => $request->tax_rate,
                    'effective_date' => $request->effective_from,
                ]);
            });

            return redirect()->route('tax.index')->with('msg', __('message.edit'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('tax.index')->with('error', __('error.occurred'));
        }
    }
}
