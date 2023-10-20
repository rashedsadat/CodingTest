<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function showWithdrawal()
    {
        $withdrawals = Transaction::where('transaction_type', 'withdrawal')->get();
        return back();
    }

    public function withdrawal(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric'
        ]);

        $transaction = new Transaction();
        $transaction->user_id = $request->user_id;
        $transaction->transaction_type = 'withdrawal';
        $transaction->amount = $request->amount;
        $transaction->date = now();
        $transaction->save();

        $user = User::findOrFail($request->user_id);
        if ($user->account_type == 'individual') {
            $monthlyWithdrawal = $user->whereHas('transactions', function ($transactions) {
                $transactions->where('transaction_type', 'withdrawal')->where('date', now()->month);
            })->withSum('transactions', 'amount');
            if (now()->dayOfWeek == Carbon::FRIDAY) {
                $fee = 0;
            } else {
                if ($monthlyWithdrawal <= 5000) {
                    $fee = 0;
                } else {
                    if ($request->amount > 1000) {
                        $fee = ($request->amount - 1000) * (0.015 / 100);
                    } else {
                        $fee = ($request->amount - 1000) * (0.015 / 100);
                    }
                }
            }
        } else {
            $totalWithdrawal = $user->whereHas('transactions', function ($transactions) {
                $transactions->where('transaction_type', 'withdrawal');
            })->withSum('transactions', 'amount');
            if ($totalWithdrawal >= 50000) {
                $fee = $request->amount * (0.015 / 100);
            } else {
                $fee = $request->amount * (0.025 / 100);
            }
        }

        $user->balance -= ($request->amount + $fee);
        $user->save();

        $transaction->fee = $fee;
        $transaction->save();
    }
}
