<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function track(Request $request)
    {
        $transactions = null;
        
        if ($request->has('search_value') && $request->has('search_type')) {
            $searchValue = $request->search_value;
            $searchType = $request->search_type;
            
            $query = Transaction::with(['customer', 'package']);
            
            switch ($searchType) {
                case 'invoice':
                    $query->where('invoice_number', 'like', '%' . $searchValue . '%');
                    break;
                case 'name':
                    $query->whereHas('customer', function($q) use ($searchValue) {
                        $q->where('name', 'like', '%' . $searchValue . '%');
                    });
                    break;
                case 'phone':
                    $query->whereHas('customer', function($q) use ($searchValue) {
                        $q->where('phone', 'like', '%' . $searchValue . '%');
                    });
                    break;
            }
            
            $transactions = $query->latest()->get();
        }
        
        return view('track', compact('transactions'));
    }
}
