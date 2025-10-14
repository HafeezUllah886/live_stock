<?php

namespace App\Http\Controllers;

use App\Models\Slaughtering;
use App\Http\Controllers\Controller;
use App\Models\accounts;
use App\Models\products;
use Illuminate\Http\Request;

class SlaughteringController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $from = $request->from ?? firstDayOfMonth();
        $to = $request->to ?? date('Y-m-d');
        $slaughterings = Slaughtering::whereBetween('date', [$from, $to])->get();
        return view('slaughterings.index', compact('slaughterings', 'from', 'to'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = products::orderby('name', 'asc')->get();
        $customers = accounts::customer()->get();
        $factories = accounts::factory()->get();
        $butchers = accounts::butcher()->get();
        return view('slaughtering.create', compact('products', 'customers', 'factories', 'butchers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Slaughtering $slaughtering)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Slaughtering $slaughtering)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Slaughtering $slaughtering)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slaughtering $slaughtering)
    {
        //
    }
}
