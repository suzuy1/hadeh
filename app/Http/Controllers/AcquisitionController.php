<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Acquisition;
use App\Models\Item;
use App\Models\User;

class AcquisitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $acquisitions = Acquisition::with(['item', 'user'])->get();
        return view('acquisitions.index', compact('acquisitions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $items = Item::all();
        $users = User::all();
        return view('acquisitions.create', compact('items', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'acquisition_date' => 'required|date',
            'source' => 'required|max:255',
            'price' => 'nullable|numeric|min:0',
            'notes' => 'nullable',
            'user_id' => 'required|exists:users,id',
        ]);

        Acquisition::create($validatedData);

        return redirect()->route('acquisitions.index')->with('success', 'Acquisition created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Acquisition $acquisition)
    {
        return view('acquisitions.show', compact('acquisition'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Acquisition $acquisition)
    {
        $items = Item::all();
        $users = User::all();
        return view('acquisitions.edit', compact('acquisition', 'items', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Acquisition $acquisition)
    {
        $validatedData = $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'acquisition_date' => 'required|date',
            'source' => 'required|max:255',
            'price' => 'nullable|numeric|min:0',
            'notes' => 'nullable',
            'user_id' => 'required|exists:users,id',
        ]);

        $acquisition->update($validatedData);

        return redirect()->route('acquisitions.index')->with('success', 'Acquisition updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Acquisition $acquisition)
    {
        $acquisition->delete();

        return redirect()->route('acquisitions.index')->with('success', 'Acquisition deleted successfully!');
    }
}
