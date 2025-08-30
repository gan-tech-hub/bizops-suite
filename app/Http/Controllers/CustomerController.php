<?php

namespace App\Http\Controllers;

use App\Models\Customer;
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
        return view('customers.create');
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
        ]);
        Customer::create($validated);
        return redirect()->route('customers.index')->with('success', '顧客を登録しました。');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customer = Customer::findOrFail($id); // ← これで $customer を取得
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $customer = Customer::findOrFail($id); // ← これで $customer を取得
        $validated = $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|max:20',
            'address' => 'nullable|max:100',
        ]);
        $customer->update($validated);
        return redirect()->route('customers.index')->with('success', '顧客情報を更新しました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = Customer::findOrFail($id); // ← これで $customer を取得
        $customer->delete();
        return redirect()->route('customers.index')->with('success', '顧客を削除しました。');
    }
}
