<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'position' => 'nullable|string|max:255',
            'role' => 'required|in:general,admin',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'position' => $validated['position'] ?? null,
            'role' => $validated['role'],
        ]);

        return redirect()->route('staffs.index')->with('success', 'ユーザーを登録しました');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
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

        return redirect()->route('users.index')->with('success', 'ユーザー情報を更新しました。');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'ユーザー情報を削除しました。');
    }
}

