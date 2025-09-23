<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        $customers = $query->get();
        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $staffs = User::all();
        return view('customers.create', compact('staffs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|max:20',
            'address' => 'nullable|max:100',
            'staff_id' => 'nullable|exists:users,id',
        ]);
        Customer::create($validated);
        return redirect()->route('customers.index')->with('success', '顧客情報を登録しました。');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $customer = Customer::with('reservations')->findOrFail($id);
        $staffs = User::all();
        return view('customers.show', compact('customer', 'staffs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customer = Customer::findOrFail($id);
        $staffs = User::all();
        return view('customers.edit', compact('customer', 'staffs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $customer = Customer::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|max:20',
            'address' => 'nullable|max:100',
            'staff_id' => 'nullable|exists:users,id',
        ]);
        $customer->update($validated);
        return redirect()->route('customers.index')->with('success', '顧客情報を更新しました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return redirect()->route('customers.index')->with('success', '顧客情報を削除しました。');
    }
}
