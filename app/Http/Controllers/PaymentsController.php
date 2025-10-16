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
use App\Models\staffPayments;
use App\Models\transactions;
use App\Models\transactions_que;
use App\Models\User;
use App\Models\users_transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $start = $request->start ?? date('Y-m-d');
        $end = $request->end ?? date('Y-m-d');

        $payments = payments::whereBetween('date', [$start, $end])->orderBy('id', 'desc')->get();

        $receivers = accounts::notBusiness()->active()->get();

        $accounts = accounts::business()->active()->get();
        return view('Finance.payments.index', compact('payments', 'receivers', 'accounts', 'start', 'end'));
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
            payments::create(
                [
                    'account_id'    => $request->account_id,
                    'receiver_id'    => $request->receiver_id,
                    'date'          => $request->date,
                    'amount'        => $request->amount,
                    'notes'         => $request->notes,
                    'refID'         => $ref,
                ]
            );
            $receiver = accounts::find($request->receiver_id);
            
            $notes = "Payment to $receiver->title ($receiver->type) Notes : $request->notes";
            createTransaction($request->receiver_id, $request->date, $request->amount, 0, $notes, $ref);
            createTransaction($request->account_id, $request->date, 0, $request->amount, $request->notes, $ref);
           
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
        $payment = payments::find($id);
       
        return view('Finance.payments.receipt', compact('payment'));
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
            payments::where('refID', $ref)->delete();
            transactions::where('refID', $ref)->delete();
         
            DB::commit();
            session()->forget('confirmed_password');
            return redirect()->route('payments.index')->with('success', "Payment Deleted");
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            session()->forget('confirmed_password');
            return redirect()->route('payments.index')->with('error', $e->getMessage());
        }
    }
}
