<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation; // ← これを追加

class ReservationController extends Controller
{
    // API用
    public function index()
    {
        return Reservation::all();
    }

    // ビュー用
    public function view()
    {
        return view('reservations.index');
    }

#    public function index()
#    {
#        $reservations = Reservation::all();

#        // FullCalendar用の形式に変換
#        $events = $reservations->map(function ($reservation) {
#            return [
#                'id' => $reservation->id,
#                'title' => $reservation->title, // ← これがカレンダーに表示される
#                'start' => $reservation->start,
#                'end' => $reservation->end,
#            ];
#        });

 #       return response()->json($events);
 #   }

    public function apiIndex()
    {
        return Reservation::all()->map(function($r){
            return [
                'id' => $r->id,
                'title' => $r->title,
                'start' => $r->start_datetime,
                'end' => $r->end_datetime,
            ];
        });
    }

    public function apiStore(Request $request)
    {
        $reservation = Reservation::create([
            'title' => $request->title,
            'start_datetime' => $request->start,
            'end_datetime' => $request->end,
        ]);
        return response()->json($reservation);
    }

    public function apiUpdate(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update([
            'title' => $request->title ?? $reservation->title,
            'start_datetime' => $request->start,
            'end_datetime' => $request->end,
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
