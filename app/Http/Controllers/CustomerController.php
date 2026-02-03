<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        // Apply filters
        if ($request->filled('is_member')) {
            $query->where('is_member', $request->is_member);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('created_at', 'desc')->get();

        // Format data for DataTable
        $formattedCustomers = $customers->map(function ($customer) {
            return [
                'id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'address' => $customer->address,
                'is_member' => $customer->is_member,
                'points' => $customer->points,
                'total_transactions' => $customer->total_transactions,
                'total_spent' => $customer->total_spent,
                'member_since' => $customer->member_since ? $customer->member_since->translatedFormat('d M Y') : '-',
                'created_at' => $customer->created_at->translatedFormat('d M Y'),
            ];
        });

        return view('customers.index', compact('formattedCustomers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:customers,phone',
            'address' => 'nullable|string',
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil ditambahkan!');
    }

    public function show(Customer $customer)
    {
        $customer->load('transactions.package');
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:customers,phone,' . $customer->id,
            'address' => 'nullable|string',
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil diupdate!');
    }

    public function destroy(Customer $customer)
    {
        if ($customer->transactions()->count() > 0) {
            return back()->withErrors(['error' => 'Customer tidak dapat dihapus karena memiliki transaksi!']);
        }

        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil dihapus!');
    }

    public function toggleMember(Customer $customer)
    {
        if ($customer->is_member) {
            $customer->update([
                'is_member' => false,
                'member_since' => null,
            ]);
            $message = 'Customer berhasil dihapus dari member!';
        } else {
            $customer->becomeMember();
            $message = 'Customer berhasil dijadikan member!';
        }

        return redirect()->route('customers.index')
            ->with('success', $message);
    }
}
