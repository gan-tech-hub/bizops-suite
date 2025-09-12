<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;

class ReservationController extends Controller
{
    public function view()
    {
        return view('reservations.index');
    }

    public function index()
    {
        $reservations = Reservation::all();

        // FullCalendar用形式
        $events = $reservations->map(function ($reservation) {
            return [
                'id' => $reservation->id,
                'title' => $reservation->title ? $reservation->title->toIso8601String() : null,
                'color' => $reservation->color ? $reservation->color->toIso8601String() : null,
                'start' => $reservation->start ? $reservation->start->toIso8601String() : null,
                'end' => $reservation->end ? $reservation->end->toIso8601String() : null,
                'location' => $reservation->location ? $reservation->location->toIso8601String() : null,
                'staff' => $reservation->staff ? $reservation->staff->toIso8601String() : null,
                'customer' => $reservation->customer ? $reservation->customer->toIso8601String() : null,
                'description' => $reservation->description ? $reservation->description->toIso8601String() : null,
            ];
        });

        return response()->json($events);
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()->route('reservations.view')->with('success', '予約を削除しました');
    }

    public function edit(Reservation $reservation)
    {
        return view('reservations.edit', compact('reservation'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $reservation->update($request->all());
        return redirect()->route('reservations.view')->with('success', '予約を更新しました');
/*
        $reservation->update($request->only([
            'title',
            'start',
            'end',
            'location',
            'description',
            'customer_id',
        ]));
        return redirect()->route('reservations.view')->with('success', '予約を更新しました');
*/
    }

    public function store(Request $request)
    {

        Reservation::create($request->all());
        return redirect()->route('reservations.view')->with('success', '予約を追加しました');
/*
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'required|date|after:start',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $reservation = Reservation::create($validated);

        return redirect()->route('reservations.view')->with('success', '予約を追加しました');
*/
    }

    public function apiIndex()
    {
        return Reservation::all()->map(function ($r) {
            return [
                'id' => $r->id,
                'title' => $r->title,
                'color' => $r->color,
                'start' => $r->start,
                'end' => $r->end,
                'location' => $r->location,
                'staff' => $r->staff,
                'customer' => $r->customer,
                'description' => $r->description,
            ];
        });
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
