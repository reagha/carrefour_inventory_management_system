<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index() {
        $suppliers = Supplier::withCount('products')->get();
        return view('suppliers.index', compact('suppliers'));
    }

    public function create() {
        return view('suppliers.create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:suppliers',
            'contact_email' => 'required|email|unique:suppliers',
            'contact_phone' => 'required|string|max:20',
        ]);

        Supplier::create($validated);
        return redirect()->route('suppliers.index')->with('success', 'Supplier added.');
    }

    public function edit(Supplier $supplier) {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier) {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name,'.$supplier->id,
            'contact_email' => 'required|email|unique:suppliers,contact_email,'.$supplier->id,
            'contact_phone' => 'required|string|max:20',
        ]);

        $supplier->update($validated);
        return redirect()->route('suppliers.index')->with('success', 'Supplier updated.');
    }

    public function destroy(Supplier $supplier) {
        // CONSTRAINT LOGIC: Check if supplier has products or purchase orders
        if ($supplier->products()->exists() || $supplier->purchaseOrders()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete: This supplier has associated products or order history.');
        }

        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier removed.');
    }
}