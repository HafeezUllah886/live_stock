<?php

namespace App\Http\Controllers;

use App\Models\Slaughtering;
use App\Http\Controllers\Controller;
use App\Models\accounts;
use App\Models\products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        return view('slaughtering.index', compact('slaughterings', 'from', 'to'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = products::active()->get();
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
        $slaughtering_amount = $request->qty * $request->slaughter_charges;
        $total = $request->weight * $request->price;
        $rejected_total = $request->rejected_weight * $request->rejected_price;
        $ober_total = $request->ober_qty * $request->ober_price;
        $grand_total = $slaughtering_amount + $total + $rejected_total + $ober_total;
       try
       {
        DB::beginTransaction();
        $ref = getRef();
        Slaughtering::create(
            [
                'date' => $request->date,
                'factory_id' => $request->factory_id,
                'customer_id' => $request->customer_id,
                'product_id' => $request->product_id,
                'slaughtering_amount' => $slaughtering_amount,
                'slaughtering_charges' => $request->slaughter_charges,
                'qty' => $request->qty,
                'weight' => $request->weight,
                'price' => $request->price,
                'amount' => $total,
                'rejected_weight' => $request->rejected_weight,
                'rejected_price' => $request->rejected_price,
                'rejected_amount' => $rejected_total,
                'ober_qty' => $request->ober_qty,
                'ober_price' => $request->ober_price,
                'ober_amount' => $ober_total,
                'grand_total' => $grand_total,
                'butcher_id' => $request->butcher_id,
                'ober_id' => $request->ober_customer_id,
                'notes' => $request->notes,
                'refID' => $ref,
            ]
        );

        $product = products::find($request->product_id);
        $factory = accounts::find($request->factory_id);
        $customer = accounts::find($request->customer_id);
        $butcher = accounts::find($request->butcher_id);
        $ober_customer = accounts::find($request->ober_customer_id);

        $notes_for_customer = "Slaughtering of " . $request->weight . "Kg " . $product->name . " at " . $request->price . " per Kg in " . $factory->title;
        $notes_for_slaughtering_charges = "Slaughtering Amount of " . $request->qty . " " . $product->name . " at " . $request->slaughter_charges . " per animal";
        createTransaction($customer->id, $request->date, $total, 0,$notes_for_customer, $ref);
        createTransaction($customer->id, $request->date, 0, $slaughtering_amount, $notes_for_slaughtering_charges, $ref);

        $notes_for_butcher = "Pending Amount of " . $request->rejected_weight . "Kg " . $product->name . " at " . $request->rejected_price . " per Kg in " . $factory->title;
        createTransaction($butcher->id, $request->date, $rejected_total, 0, $notes_for_butcher, $ref);

        $notes_for_ober = "Pending Amount of " . $request->ober_qty . " ober of " . $product->name . " at " . $request->ober_price . " per ober in " . $factory->title;
        createTransaction($ober_customer->id, $request->date, $ober_total, 0, $notes_for_ober, $ref);

        DB::commit();
        return redirect()->route('slaughter.index')->with('success', 'Slaughtering created successfully');
       }
       catch(Exception $e)
       {
        DB::rollBack();
        return redirect()->route('slaughter.index')->with('error', 'Slaughtering created failed');
       }
       
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
