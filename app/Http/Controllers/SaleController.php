<?php

namespace App\Http\Controllers;

use App\Http\Middleware\confirmPassword;
use App\Models\accounts;
use App\Models\categories;
use App\Models\products;
use App\Models\sale;
use App\Models\sale_details;
use App\Models\stock;
use App\Models\transactions;
use App\Models\units;
use App\Models\warehouses;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;

class SaleController extends Controller
{
    public function __construct()
    {
        // Apply middleware to the edit method
        $this->middleware(confirmPassword::class)->only('edit');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $from = $request->from ?? firstDayOfMonth();
        $to = $request->to ?? date('Y-m-d');
        $sales = sale::whereBetween('date', [$from, $to])->get();
        return view('sale.index', compact('sales', 'from', 'to'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = products::active()->get();
        $customers = accounts::customerAndFactory()->get();
        $accounts = accounts::business()->get();
        return view('sale.create', compact('products', 'customers', 'accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try
        {
            if($request->isNotFilled('id'))
            {
                throw new Exception('Please Select Atleast One Product');
            }
            DB::beginTransaction();
            $ref = getRef();
            $sale = sale::create(
                [
                  'customer_id'        => $request->customerID,
                  'date'            => $request->date,
                  'notes'           => $request->notes,
                  'customerName'      => $request->customerName,
                  'payment_status'  => $request->status,
                  'refID'           => $ref,
                ]
            );

            $ids = $request->id;
            $total = 0;
            $note_details = "";
            foreach($ids as $key => $id)
            {
                if($request->qty[$key] > 0)
                {
                $qty = $request->qty[$key]; 
                $price = $request->price[$key];
                $amount = $price * $qty;
                $total += $amount;

                sale_details::create(
                    [
                        'sale_id'    => $sale->id,
                        'product_id'     => $id,
                        'price'         => $price,
                        'qty'           => $qty,
                        'amount'        => $amount,
                        'date'          => $request->date,
                        'refID'         => $ref,
                    ]
                );
                $product = products::find($id);
                $note_details .= $qty . "x " . $product->name . " at " . number_format($price) . "<br>";
                createStock($id, 0, $qty, $request->date, "Sold Notes: $request->notes", $ref);

                }
            }

            $sale->update(
                [
                    'total' => $total,
                ]
            );

            if($request->status == 'paid')
            {
                createTransaction($request->customerID, $request->date, $total, $total, "Payment of Sale No. $sale->id <br> $note_details", $ref, 'Sale');
                createTransaction($request->accountID, $request->date, $total, 0, "Payment of Sale No. $sale->id <br> $note_details", $ref, 'Sale');
            }
            else
            {
                createTransaction($request->customerID, $request->date, $total, 0, "Pending Amount of Sale No. $sale->id <br> $note_details", $ref, 'Sale');
            }
            DB::commit();
            return back()->with('success', "Sale Created");

        }
        catch(\Exception $e)
        {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(sale $sale)
    {
        return view('sale.view', compact('sale'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(sale $sale)
    {
        $products = products::orderby('name', 'asc')->get();
     
        $customers = accounts::customerAndFactory()->get();
        $accounts = accounts::business()->get();

        return view('sale.edit', compact('products', 'customers', 'accounts', 'sale'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, sale $sale)
    {
        try
        {
            if($request->isNotFilled('id'))
            {
                throw new Exception('Please Select Atleast One Product');
            }
            DB::beginTransaction();
          
            foreach($sale->details as $product)
            {
                stock::where('refID', $product->refID)->delete();
                $product->delete();
            }
            transactions::where('refID', $sale->refID)->delete();

            $sale->update(
                [
              'customer_id'        => $request->customerID,
                  'date'            => $request->date,
                  'notes'           => $request->notes,
                  'customerName'      => $request->customerName,
                  'payment_status'  => $request->status,
                  ]
            );

            $ids = $request->id;
            $ref = $sale->refID;

            $total = 0;
            $note_details = "";
            foreach($ids as $key => $id)
            {
                if($request->qty[$key] > 0)
                {
                    $qty = $request->qty[$key];
              
                $price = $request->price[$key];
                $amount = $price * $qty;
                $total += $amount;

                sale_details::create(
                    [
                        'sale_id'    => $sale->id,
                        'product_id'     => $id,
                        'price'         => $price,
                        'qty'           => $qty,
                        'amount'        => $amount,
                        'date'          => $request->date,
                        'refID'         => $ref,
                    ]
                );
                $product = products::find($id);
                $note_details .= $qty . "x " . $product->name . " at " . number_format($price) . "<br>";
                createStock($id, $qty, 0, $request->date, "Purchased", $ref);

                }
            }
            $sale->update(
                [

                    'total'       => $total,
                ]
            );

             if($request->status == 'paid')
            {   
                createTransaction($request->customerID, $request->date, $total, $total, "Payment of Sale No. $sale->id <br> $note_details", $ref, 'Sale');
                createTransaction($request->accountID, $request->date, $total, 0, "Payment of Sale No. $sale->id <br> $note_details", $ref, 'Sale');
            }
            else
            {
                createTransaction($request->customerID, $request->date, $total, 0, "Pending Amount of Sale No. $sale->id <br> $note_details", $ref, 'Sale');
            }
            DB::commit();
            session()->forget('confirmed_password');
            return to_route('sale.index')->with('success', "Sale Updated");
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        try
        {
            DB::beginTransaction();
            $sale = sale::find($id);
            foreach($sale->details as $product)
            {
                stock::where('refID', $product->refID)->delete();
                $product->delete();
            }
            transactions::where('refID', $sale->refID)->delete();
            $sale->delete();
            DB::commit();
            session()->forget('confirmed_password');
            return to_route('sale.index')->with('success', "Sale Deleted");
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            session()->forget('confirmed_password');
            return to_route('sale.index')->with('error', $e->getMessage());
        }
    }

    public function getSignleProduct($id)
    {
        $product = products::find($id);
        return $product;
    }
}
