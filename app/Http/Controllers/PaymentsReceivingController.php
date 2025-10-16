<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\accounts;
use App\Models\area;
use App\Models\cheques;
use App\Models\currency_transactions;
use App\Models\currencymgmt;
use App\Models\method_transactions;
use App\Models\payments;
use App\Models\paymentsReceiving;
use App\Models\transactions;
use App\Models\transactions_que;
use App\Models\User;
use App\Models\users_transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentsReceivingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $start = $request->start ?? date('Y-m-d');
        $end = $request->end ?? date('Y-m-d');
       
        $payments = paymentsReceiving::orderBy('id', 'desc')->whereBetween('date', [$start, $end])->get();

        $depositers = accounts::notBusiness()->get();
        $accounts = accounts::business()->get();    

        return view('Finance.payments_receiving.index', compact('payments', 'depositers', 'accounts', 'start', 'end'));
    }

    /**
     * Show the form for creating a new resource.
     */ 
    public function create()    
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{ 
            DB::beginTransaction();
            $ref = getRef();
            paymentsReceiving::create(
                [
                    'depositer_id'  => $request->depositer_id,
                    'account_id'    => $request->account_id,
                    'date'          => $request->date,
                    'amount'        => $request->amount,
                    'notes'         => $request->notes,
                    'refID'         => $ref,
                ]
            );
            $depositer = accounts::find($request->depositer_id);
            $account = accounts::find($request->account_id);

            $notes = "Payment received from $depositer->title ($depositer->category) Notes : $request->notes";
            $notes1 = "Payment deposited in $account->title ($account->category) Notes : $request->notes";

            createTransaction($request->depositer_id, $request->date, 0, $request->amount, $notes1, $ref);
            createTransaction($request->account_id, $request->date, $request->amount, 0, $notes, $ref);
        
          DB::commit();
            return back()->with('success', "Payment Saved");
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        } 
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $payment = paymentsReceiving::find($id);
       
        return view('Finance.payments_receiving.receipt', compact('payment', 'currencies'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(vendorPayments $vendorPayments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, vendorPayments $vendorPayments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($ref)
    {
        try
        {
            DB::beginTransaction();
            paymentsReceiving::where('refID', $ref)->delete();
            transactions::where('refID', $ref)->delete();
           
            DB::commit();
            session()->forget('confirmed_password');
            return redirect()->route('payments_receiving.index')->with('success', "Payment Deleted");
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            session()->forget('confirmed_password');
            return redirect()->route('payments_receiving.index')->with('error', $e->getMessage());
        }
    }
}
