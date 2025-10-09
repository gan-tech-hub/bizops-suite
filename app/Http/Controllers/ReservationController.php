<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Customer;
use App\Models\User;

class ReservationController extends Controller
{
    public function view()
    {
        $user = auth()->user();
        $staffs = User::all();

        if ($user->isAdmin()) {
            $reservations = Reservation::with(['customer', 'staff'])->get();
            $customers = Customer::with('staff')->get();
        } else {
            $reservations = Reservation::with(['customer', 'staff'])->where('staff_id', $user->id)->get();
            $customers = Customer::with('staff')->where('staff_id', $user->id)->get();
        }

        return response()
            ->view('reservations.index', compact('reservations', 'customers', 'staffs'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }

    public function create()
    {
        $customers = Customer::all();
        $staffs = User::all();
        return view('reservations.create', compact('customers', 'staffs'));
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
            'staff_id' => 'required|exists:users,id',
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
            'end' => [
                'nullable',
                'date',
                // start より後であることをチェック
                function ($attribute, $value, $fail) use ($request) {
                    if ($value && strtotime($value) <= strtotime($request->start)) {
                        $fail('終了日時は開始日時より後に設定してください。');
                    }
                },
            ],
            'customer_id' => 'nullable|exists:customers,id',
            'color' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'staff_id' => 'nullable|exists:users,id',
            'description' => 'nullable|string',
        ]);

        $reservation->update($validated);

        if ($request->filled('redirect_to')) {
            return redirect($request->redirect_to)->with('success', '予約を更新しました');
        }

        return redirect()->route('reservations.view')->with('success', '予約を更新しました');
    }

    public function edit(Reservation $reservation)
    {
        $customers = Customer::all();
        $staffs = User::all();
        return view('reservations.edit', compact('reservation', 'customers', 'staffs'));
    }

    public function destroy(Request $request, Reservation $reservation)
    {
        $reservation->delete();

        if ($request->filled('redirect_to')) {
            return redirect($request->redirect_to)->with('success', '予約を削除しました');
        }

        return redirect()->route('reservations.view')->with('success', '予約を削除しました');
    }

    public function apiIndex()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            // 管理者は全予約取得
            $reservations = Reservation::with(['customer', 'staff'])->get();
        } else {
            // 一般ユーザは自分の担当予約のみ取得
            $reservations = Reservation::with(['customer', 'staff'])
                                    ->where('staff_id', $user->id)
                                    ->get();
        }

        return response()->json($reservations->map(function ($r) {
            return [
                'id' => $r->id,
                'title' => $r->title,
                'color' => $r->color,
                'start' => $r->start ? $r->start->toIso8601String() : null,
                'end' => $r->end ? $r->end->toIso8601String() : null,
                'location' => $r->location,
                'description' => $r->description,
                'staff_name' => $r->staff?->name,
                'staff_id' => $r->staff?->id,
                'customer_name' => $r->customer?->name,
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
