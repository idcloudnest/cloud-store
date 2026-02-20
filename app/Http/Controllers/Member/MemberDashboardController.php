<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberDashboardController extends Controller
{
	public function index()
	{
		$user = Auth::user();

		// Ambil 5 transaksi terakhir untuk widget history
		$recentTransactions = $user->transactions()
								   ->latest()
								   ->take(5)
								   ->get();

		// Hitung pengeluaran bulan ini (Opsional)
		$expenseThisMonth = $user->transactions()
								 ->whereMonth('created_at', now()->month)
								 ->where('payment_status', 'paid')
								 ->sum('amount');

		return view('member.dashboard', compact('user', 'recentTransactions', 'expenseThisMonth'));
	}
}
