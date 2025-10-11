<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Unit; // Import the Unit model

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::with('unit')->get(); // Eager load the unit relationship
        return view('rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $units = Unit::all(); // Get all units
        return view('rooms.create', compact('units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_ruangan' => 'required|max:255',
            'lokasi' => 'nullable|max:255',
            'unit_id' => 'nullable|exists:units,id',
        ]);

        Room::create($validatedData);

        return redirect()->route('rooms.index')->with('success', 'Room created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        $room->load('unit'); // Eager load the unit relationship
        return view('rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        $units = Unit::all(); // Get all units
        return view('rooms.edit', compact('room', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        $validatedData = $request->validate([
            'nama_ruangan' => 'required|max:255',
            'lokasi' => 'nullable|max:255',
            'unit_id' => 'nullable|exists:units,id',
        ]);

        $room->update($validatedData);

        return redirect()->route('rooms.index')->with('success', 'Room updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'Room deleted successfully!');
    }
}
