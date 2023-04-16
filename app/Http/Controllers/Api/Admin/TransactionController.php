<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    public function transactionlist(){
        $transaction = Transaction::with('sender', 'receiver', 'naturetransaction')->orderByDesc('id')->get();
        return response()->json([
            'status' => 200,
            'transaction' => $transaction,
        ]);
    }

    public function transactiondetails($id){
        $transaction = Transaction::with('sender', 'receiver', 'naturetransaction')->find($id);
        if($transaction){
            return response()->json([
                'status' => 200,
                'transaction' => $transaction,
            ]);
        }
    }
}
