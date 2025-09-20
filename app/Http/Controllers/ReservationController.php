<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Customer;

class ReservationController extends Controller
{
    public function view()
    {
        $customers = Customer::all();

        return view('reservations.index', compact('customers'));
    }

    public function create()
    {
        $customers = Customer::all();
        return view('reservations.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'customer_id' => 'nullable|exists:customers,id',
            'color' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'staff' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);
        Reservation::create($validated);
        return redirect()->route('reservations.view')->with('success','予約を追加しました');
    }

    public function update(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'customer_id' => 'nullable|exists:customers,id',
            'color' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'staff' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);
        $reservation->update($validated);
        return redirect()->route('reservations.view')->with('success','予約を更新しました');
    }

    public function edit(Reservation $reservation)
    {
        $customers = Customer::all();
        return view('reservations.edit', compact('reservation', 'customers'));
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()->route('reservations.view')->with('success', '予約を削除しました');
    }

    public function apiIndex()
    {
        $reservations = Reservation::with('customer')->get();

        return response()->json($reservations->map(function ($r) {
            return [
                'id' => $r->id,
                'title' => $r->title,
                'color' => $r->color,
                'start' => $r->start ? $r->start->toIso8601String() : null,
                'end' => $r->end ? $r->end->toIso8601String() : null,
                'location' => $r->location,
                'staff' => $r->staff,
                'customer_name' => $r->customer?->name,
                'description' => $r->description,
                'customer_id' => $r->customer?->id,
            ];
        }));
    }

    public function apiStore(Request $request)
    {
        $reservation = Reservation::create([
            'title' => $request->title,
            'color' => $request->color,
            'start' => $request->start,
            'end' => $request->end,
            'customer_id' => $request->customer_id,
            'location' => $request->location,
            'staff' => $request->staff,
            'customer' => $request->customer,
            'description' => $request->description,
        ]);
        return response()->json($reservation);
    }

    public function apiUpdate(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update([
            'title' => $request->title ?? $reservation->title,
            'color' => $request->color ?? $reservation->color,
            'start' => $request->start ?? $reservation->start,
            'end' => $request->end ?? $reservation->end,
            'customer_id' => $request->customer_id ?? $reservation->customer_id,
            'location' => $request->location ?? $reservation->location,
            'staff' => $request->staff ?? $reservation->staff,
            'customer' => $request->customer ?? $reservation->customer,
            'description' => $request->description ?? $reservation->description,
        ]);
        return response()->json($reservation);
    }

    public function apiDestroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();
        return response()->json(['status' => 'deleted']);
    }
}
