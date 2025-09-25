<?php

namespace App\Http\Controllers;

use App\Models\accounts;
use App\Models\branches;
use App\Models\method_transactions;
use App\Models\transactions;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountsController extends Controller
{
    public function index($filter)
    {
        $accounts = accounts::where('category', $filter)->orderBy('title', 'asc')->get();

        return view('finance.accounts.index', compact('accounts', 'filter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('finance.accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'title' => 'required',
            ],
            [
                'title.required' => "Please Enter Account Title",
            ]
        );

        try
        {
            DB::beginTransaction();

            $account = accounts::create(
                [
                    'title' => $request->title,
                    'type' => $request->type ?? "Cash",
                    'category' => $request->category,
                    'contact' => $request->contact,
                    'address' => $request->address,
                ]
            );
               
           DB::commit();
           return back()->with('success', "Account Created Successfully");
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
    public function show($id, $from, $to)
    {
        $account = accounts::find($id);

        $transactions = transactions::where('account_id', $id)->whereBetween('date', [$from, $to]);
        $transactions = $transactions->orderBy('date', 'asc')->orderBy('refID', 'asc')->get();

        $pre_cr = transactions::where('account_id', $id)->whereDate('date', '<', $from);
        $pre_cr = $pre_cr->sum('cr');

        $pre_db = transactions::where('account_id', $id)->whereDate('date', '<', $from);
        $pre_db = $pre_db->sum('db');

        $pre_balance = $pre_cr - $pre_db;

        $cur_balance = getAccountBalance($id);

        return view('finance.accounts.statment', compact('account', 'transactions', 'pre_balance', 'cur_balance', 'from', 'to'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(accounts $account)
    {
        return view('finance.accounts.edit', compact('account'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'title' => "required",
            ],
            [
                'title.required' => "Please Enter Account Title",
            ]
        );
        $account = accounts::find($id)->update(
            [
                'title' => $request->title,
                'contact' => $request->contact ?? null,
                'address' => $request->address ?? null,
                'type' => $request->type ?? "Cash",
            ]
        );

        return redirect()->route('accountsList', $account->category)->with('success', "Account Updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(accounts $accounts)
    {
        //
    }

    public function status($id)
    {
        $account = accounts::find($id);
        if($account->status == "Active")
        {
           $status = "Inactive";
        }
        else
        {
            $status = "Active";
        }

        $account->update(
            [
                'status' => $status,
            ]
        );

        return back()->with('success', "Status Updated");
    }
}
