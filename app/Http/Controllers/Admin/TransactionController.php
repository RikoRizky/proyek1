<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::latest()->paginate(15);
        $totalRevenue = Transaction::where('status', 'success')->sum('amount');
        
        $starterCount = \App\Models\User::where('active_package', 'Starter')->count();
        $proCount = \App\Models\User::where('active_package', 'Pro')->count();
        $enterpriseCount = \App\Models\User::where('active_package', 'Enterprise')->count();
        
        return view('admin.transactions.index', compact('transactions', 'totalRevenue', 'starterCount', 'proCount', 'enterpriseCount'));
    }
}
