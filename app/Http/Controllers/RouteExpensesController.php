<?php

namespace App\Http\Controllers;

use App\Models\RouteExpenses;
use App\Http\Controllers\Controller;
use App\Models\accounts;
use App\Models\transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RouteExpensesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $from = $request->from ?? firstDayOfMonth();
        $to = $request->to ?? date('Y-m-d');
        $routeExpenses = RouteExpenses::whereBetween('date', [$from, $to])->orderBy('date', 'desc')->get();
        $transporters = accounts::transporter()->get();
        $accounts = accounts::business()->get();
        return view('finance.route_expense.index', compact('routeExpenses', 'from', 'to', 'transporters', 'accounts'));
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
        try {
            DB::beginTransaction();
            $ref = getRef();
            $routeExpenses = RouteExpenses::create(
                [
                    'date' => $request->date,
                    'transporter_id' => $request->transporter_id,
                    'amount' => $request->amount,
                    'notes' => $request->notes,
                    'refID' => $ref,
                ]
            );
            
            if($request->status == 'paid'){
                $notes = "Received for Route Expense : Notes: ".$request->notes;
                createTransaction($request->transporter_id, $request->date, $request->amount, $request->amount, $notes, $ref);
                $transporter = accounts::find($request->transporter_id)->title;
                $notes = "Paid for Route Expense to $transporter : Notes: ".$request->notes;
                createTransaction($request->account_id, $request->date, 0, $request->amount, $notes, $ref);
            }
            else
            {
                $notes = "Pending amount for Route Expense : Notes: ".$request->notes;
                createTransaction($request->transporter_id, $request->date, 0, $request->amount, $notes, $ref);
            }

            DB::commit();
            return redirect()->route('route_expenses.index')->with('success', 'Route Expense created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('route_expenses.index')->with('error', 'Failed to create Route Expense');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(RouteExpenses $routeExpenses)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RouteExpenses $routeExpenses)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RouteExpenses $routeExpenses)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($ref)
    {
        try {
            DB::beginTransaction();
            RouteExpenses::where('refID', $ref)->delete();
            transactions::where('refID', $ref)->delete();
            DB::commit();
            session()->forget('confirmed_password');
            return redirect()->route('route_expenses.index')->with('success', 'Route Expense deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->forget('confirmed_password');
            return redirect()->route('route_expenses.index')->with('error', 'Failed to delete Route Expense');
        }
    }
}
