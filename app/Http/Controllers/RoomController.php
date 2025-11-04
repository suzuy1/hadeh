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
    public function index(Request $request) // Tambahkan Request $request
    {
        $this->authorize('viewAny', Room::class); // Proteksi
        $rooms = Room::with('unit')->paginate(10); // Langsung perbaiki paginasi
        return view('rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Room::class); // Proteksi
        $units = Unit::all();
        return view('rooms.create', compact('units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Room::class); // Proteksi
        $request->validate([
            'nama_ruangan' => 'required|string|max:255',
            'unit_id' => 'required|exists:units,id',
        ]);
        Room::create($request->all());
        return redirect()->route('rooms.index')->with('success', 'Room created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        $this->authorize('view', $room); // Proteksi
        return view('rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        $this->authorize('update', $room); // Proteksi
        $units = Unit::all();
        return view('rooms.edit', compact('room', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        $this->authorize('update', $room); // Proteksi
        $request->validate([
            'nama_ruangan' => 'required|string|max:255',
            'unit_id' => 'required|exists:units,id',
        ]);
        $room->update($request->all());
        return redirect()->route('rooms.index')->with('success', 'Room updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        $this->authorize('delete', $room); // Proteksi
        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Room deleted successfully.');
    }
}
