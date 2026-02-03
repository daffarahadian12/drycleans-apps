<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin,owner');
    }

    public function index()
    {
        $discounts = Discount::orderBy('name')->get();

        $formattedDiscounts = $discounts->map(function ($discount) {
            return [
                'id' => $discount->id,
                'name' => $discount->name,
                'min_weight' => $discount->min_weight,
                'discount_percentage' => $discount->discount_percentage,
                'is_member_only' => $discount->is_member_only,
                'is_active' => $discount->is_active,
            ];
        });

        return view('discounts.index', compact('formattedDiscounts'));
    }

    public function create()
    {
        return view('discounts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'min_weight' => 'required|numeric|min:0',
            'discount_percentage' => 'required|numeric|min:0|max:100',
        ]);

        Discount::create([
            'name' => $request->name,
            'min_weight' => $request->min_weight,
            'discount_percentage' => $request->discount_percentage,
            'is_member_only' => $request->has('is_member_only'),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('discounts.index')->with('success', 'Diskon berhasil ditambahkan.');
    }

    public function show(Discount $discount)
    {
        return view('discounts.show', compact('discount'));
    }

    public function edit(Discount $discount)
    {
        return view('discounts.edit', compact('discount'));
    }

    public function update(Request $request, Discount $discount)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'min_weight' => 'required|numeric|min:0',
            'discount_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $discount->update([
            'name' => $request->name,
            'min_weight' => $request->min_weight,
            'discount_percentage' => $request->discount_percentage,
            'is_member_only' => $request->input('is_member_only', 0), // ambil value dari select
            'is_active' => $request->has('is_active'), // checkbox tetap pakai has()
        ]);

        return redirect()->route('discounts.index')->with('success', 'Diskon berhasil diperbarui.');
    }

    public function destroy(Discount $discount)
    {
        $discount->delete();
        return redirect()->route('discounts.index')->with('success', 'Diskon berhasil dihapus.');
    }

    public function toggleStatus(Discount $discount)
    {
        $discount->update(['is_active' => !$discount->is_active]);
        $status = $discount->is_active ? true : false;
        return redirect()
            ->route('discounts.index')
            ->with('success', "Status diskon berhasil diubah menjadi {$status}!");
    }
}
