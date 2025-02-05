<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SystemController extends Controller
{
    /**
     * indexビューを表示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('system.index');
    }

    /**
     * 言語設定を変更
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switchLanguage(Request $request)
    {
        $locale = $request->input('locale');
        try {
            Setting::updateOrCreate(['key' => 'locale'], ['value' => $locale]);
        } catch (\Exception $e) {
            return redirect()->route('system.index')->with('error', __('error.switch'));
        }
        return redirect()->route('system.index')->with('msg', __('message.switch'));
    }

    /**
     * 通貨設定を変更
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switchCurrency(Request $request)
    {
        $currency = $request->input('currency');
        try {
            Setting::updateOrCreate(['key' => 'currency'], ['value' => $currency]);
        } catch (\Exception $e) {
            return redirect()->route('system.index')->with('error', __('error.switch'));
        }
        return redirect()->route('system.index')->with('msg', __('message.switch'));
    }

    /**
     * タイムゾーン設定を変更
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switchTimezone(Request $request)
    {
        $timezone = $request->input('timezone');
        try {
            Setting::updateOrCreate(['key' => 'timezone'], ['value' => $timezone]);
        } catch (\Exception $e) {
            return redirect()->route('system.index')->with('error', __('error.switch'));
        }
        return redirect()->route('system.index')->with('msg', __('message.switch'));
    }
}
