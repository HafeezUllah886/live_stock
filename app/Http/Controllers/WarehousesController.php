<?php

namespace App\Http\Controllers;

use App\Models\warehouses;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WarehousesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $warehouses = warehouses::all();
        return view('setting.warehouses', compact('warehouses'));
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
        warehouses::create($request->all());
        return redirect()->route('warehouses.index')->with('success', 'Warehouse created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(warehouses $warehouse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(warehouses $warehouse)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, warehouses $warehouse)
    {
        $warehouse->update($request->all());
        return redirect()->route('warehouses.index')->with('success', 'Warehouse updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(warehouses $warehouse)
    {
       
    }
}
