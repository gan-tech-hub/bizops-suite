<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $customerCount = \App\Models\Customer::where('staff_id', $user->id)->count();

        $today = now()->toDateString();
        $todayReservationCount = \App\Models\Reservation::whereDate('start', '<=', $today)
            ->whereDate('end', '>=', $today)
            ->where('staff_id', $user->id)
            ->count();

        $announcements = \App\Models\Announcement::latest()->take(10)->get();
        
        return response()
            ->view('dashboard', compact('announcements', 'customerCount', 'todayReservationCount'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }
}
