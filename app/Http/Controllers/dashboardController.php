<?php

namespace App\Http\Controllers;

use App\Models\expenses;
use App\Models\export;
use App\Models\purchase;
use App\Models\OilPurchase;
use App\Models\OilProducts;
use App\Models\products;
use App\Models\purchase_details;
use App\Models\ReceiveTT;
use App\Models\RouteExpenses;
use App\Models\sale_details;
use App\Models\Slaughtering;
use App\Models\stock;
use Illuminate\Http\Request;

class dashboardController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->from ?? firstDayOfMonth();
        $to = $request->to ?? date('Y-m-d');
        
        $purchase = purchase_details::whereBetween('date', [$from, $to])
        ->selectRaw('SUM(qty) as total_qty, SUM(amount) as total_amount')
        ->first();

        $sale = sale_details::whereBetween('date', [$from, $to])
        ->selectRaw('SUM(qty) as total_qty, SUM(amount) as total_amount')
        ->first();

        $slaughter = Slaughtering::whereBetween('date', [$from, $to])
        ->selectRaw('SUM(qty) as total_qty, SUM(weight) as total_weight, SUM(amount) as total_amount, SUM(rejected_amount) as total_rejected_amount, SUM(ober_amount) as total_ober_amount, SUM(slaughtering_amount) as total_slaughtering_amount')
        ->first();

        $products = products::all();
        $stock = 0;
        foreach ($products as $product) {
            $stock += getStock($product->id);
        }

        $route_expenses = RouteExpenses::whereBetween('date', [$from, $to])
        ->selectRaw('SUM(amount) as total_amount')
        ->first();

        $expenses = expenses::whereBetween('date', [$from, $to])
        ->selectRaw('SUM(amount) as total_amount')
        ->first();

        $positive_amounts = $sale->total_amount + $slaughter->total_amount + $slaughter->rejected_amount + $slaughter->total_ober_amount;

        $negative_amounts = $purchase->total_amount + $route_expenses->total_amount + $expenses->total_amount + $slaughter->total_slaughtering_amount;

        $profit = $positive_amounts - $negative_amounts;

        return view('dashboard.index', compact('from', 'to', 'purchase', 'sale', 'slaughter', 'stock', 'route_expenses', 'expenses', 'profit'));
    }
}
