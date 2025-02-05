<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Sale;
use App\Models\SaleItem;

class DataController extends Controller
{
    /**
     * indexビューを表示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $products = Product::all();
        return view('data.index', compact('products'));
    }

    /**
     * analysisビューを表示
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function analysis(Request $request)
    {
        // endがstart日付より後、または、同じかどうか確認
        $request->validate([
            'end' => 'after_or_equal:start',
        ]);

        $startDate = Carbon::parse($request->start);
        $endDate = Carbon::parse($request->end);

        $period = CarbonPeriod::create($startDate, $endDate);

        $dates = [];
        $productId = $request->productId;

        foreach ($period as $index => $date) {
            $dates[$index]['date'] = $date->format('Y-m-d');
            $sum = 0;
            //productIdが0のレコードは存在しないので0のときに全ての商品で計算
            //1以降の整数のときはproductIdをidとしてProductテーブルから取得して計算
            switch ($productId) {
                case 0:
                    $sales = Sale::where('date', $date)->get();
                    foreach ($sales as $sale) {
                        $sum += $sale->subtotal;
                    }
                    $dates[$index]['sum'] = $sum;
                    $productName = __('data.allProducts');
                    break;
                default:
                    $sales = Sale::where('date', $date)->get();
                    $product = Product::find($productId);
                    foreach ($sales as $sale) {
                        $saleItems = SaleItem::where('sale_id', $sale->id)
                            ->where('product_id', $productId)
                            ->get();
                        foreach ($saleItems as $saleItem) {
                            $sum += $product->price * $saleItem->quantity;
                        }
                    }
                    $dates[$index]['sum'] = $sum;
                    $productName = $product->product_name;
                    break;
            }
        }
        return view('data.analysis', compact(
            'startDate',
            'endDate',
            'dates',
            'productName'
        ));
    }
}
