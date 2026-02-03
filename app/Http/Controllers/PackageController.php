<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::withCount('transactions')
            ->orderBy('name')
            ->get();

        $formattedPackages = $packages->map(function($package) {
            return [
                'id' => $package->id,
                'name' => $package->name,
                'description' => $package->description ?? '-',
                'price_per_kg' => $package->price_per_kg,
                'price_formatted' => 'Rp ' . number_format($package->price_per_kg, 0, ',', '.'),
                'estimated_days' => $package->estimated_days . ' hari',
                'is_active' => $package->is_active,
                'transactions_count' => $package->transactions_count,
            ];
        });

        return view('packages.index', compact('formattedPackages'));
    }

    public function create()
    {
        return view('packages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:packages',
            'description' => 'nullable|string',
            'price_per_kg' => 'required|numeric|min:0',
            'estimated_days' => 'required|integer|min:1',
        ]);

        Package::create([
            'name' => $request->name,
            'description' => $request->description,
            'price_per_kg' => $request->price_per_kg,
            'estimated_days' => $request->estimated_days,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('packages.index')->with('success', 'Paket laundry berhasil ditambahkan.');
    }

    public function show(Package $package)
    {
        return view('packages.show', compact('package'));
    }

    public function edit(Package $package)
    {
        return view('packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:packages,name,' . $package->id,
            'description' => 'nullable|string',
            'price_per_kg' => 'required|numeric|min:0',
            'estimated_days' => 'required|integer|min:1',
        ]);

        $package->update([
            'name' => $request->name,
            'description' => $request->description,
            'price_per_kg' => $request->price_per_kg,
            'estimated_days' => $request->estimated_days,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('packages.index')->with('success', 'Paket laundry berhasil diperbarui.');
    }

    public function destroy(Package $package)
    {
        if ($package->transactions()->count() > 0) {
            return redirect()->route('packages.index')
                ->with('error', 'Paket tidak dapat dihapus karena sudah digunakan dalam transaksi!');
        }

        $package->delete();
        return redirect()->route('packages.index')->with('success', 'Paket laundry berhasil dihapus!');
    }

    public function toggleStatus(Package $package)
    {
        $package->update(['is_active' => !$package->is_active]);
        $status = $package->is_active ? true : false;
        return redirect()->route('packages.index')->with('success', "Status paket berhasil diubah menjadi {$status}!");
    }

    public function getActive()
    {
        $packages = Package::active()->orderBy('name')->get();
        return response()->json($packages);
    }
}
