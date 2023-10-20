<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function showDeposits()
    {
        $deposits = Transaction::where('transaction_type', 'deposit')->get();
        return back();
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric'
        ]);

        $transaction = new Transaction();
        $transaction->user_id = $request->user_id;
        $transaction->transction_type = 'deposit';
        $transaction->amount = $request->amount;
        $transaction->date = now();
        $transaction->save();

        $user = User::findOrFail($request->user_id);
        $user->balance += $request->amount;
        $user->save();

        return back();
    }
}
