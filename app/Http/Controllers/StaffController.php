<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class StaffController extends Controller
{
    public function index()
    {
        $staffs = User::all();
        return response()
            ->view('staffs.index', compact('staffs'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }

    public function show(User $user)
    {
        $user->load(['customers', 'reservations']);
        return response()
            ->view('staffs.show', compact('user'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }

    public function edit(User $user)
    {
        return view('staffs.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'position' => 'nullable|string|max:255',
            'role' => 'required|in:general,admin',
        ]);

        $user->update($request->only(['name', 'email', 'position', 'role']));

        return redirect()->route('staffs.index')->with('success', '担当者情報を更新しました');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('staffs.index')->with('success', '担当者情報を削除しました');
    }

    public function create()
    {
        return view('staffs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'position' => ['nullable', 'string', 'max:255'],
            'role' => ['required', 'in:general,admin'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'position' => $request->position,
            'role' => $request->role,
        ]);

        return redirect()->route('staffs.index')->with('success', '担当者を追加しました');
    }
}
