<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function index()
    {
        $shops = Shop::whereIn('status', ['OPEN', 'CLOSED'])
            ->with('wallet')
            ->orderBy('name')
            ->get();

        return view('admin.withdrawal', compact('shops'));
    }

    public function search(Request $request)
    {
        $query = $request->get('query');

        $shops = Shop::whereIn('status', ['OPEN', 'CLOSED'])
            ->where('name', 'like', "%{$query}%")
            ->with('wallet')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'shops' => $shops
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'amount' => 'required|numeric|min:1',
        ]);

        $shop = Shop::findOrFail($request->shop_id);
        
        if (!$shop->wallet) {
            return back()->with('error', 'Shop wallet not found.');
        }

        if ($shop->wallet->balance < $request->amount) {
            return back()->with('error', 'Insufficient funds.');
        }

        $shop->wallet->debitBalance($request->amount, "Money Withdrawal");

        return back()->with('success', 'Withdrawal successful');
    }
}
