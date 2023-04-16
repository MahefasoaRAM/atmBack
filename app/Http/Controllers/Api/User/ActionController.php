<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActionController extends Controller
{
    public function viewbalance($id, $account){
        $balance = User::where('id', $id)->where('account', $account)->first();
        return response()->json([
            'status' => 200,
            'balance' => $balance->balance,
        ]);
    }

    public function transactionlist($id, $account){
        $transaction = Transaction::with('naturetransaction', 'sender', 'receiver')->where('sender_id', $id)->orWhere('receiver_id', $id)->orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 200,
            'transaction' => $transaction,
        ]);
    }

    public function withdraw(Request $request, $id, $account){
        $user = User::where('id', $id)->where('account', $account)->first();
        $amount = $request->amount;
        $nature = $request->nature;
        if($user->balance < $amount){
            return response()->json([
                'status' => 401,
                'message' => 'Error Balance insufficient',
            ]);
        } else {
            $user->debit($amount);
            Transaction::create([
                'sender_id' => $user->id,
                'receiver_id' => $user->id,
                'nature_transaction_id' => $nature,
                'amount' => $amount,
            ]);
            return response()->json([
                'status' => 200,
                'message' => 'Wtihdrawal successfully'
            ]);
        }
    }

    public function deposit(Request $request, $id, $account){
        $user = User::where('id', $id)->where('account', $account)->first();
        $amount = $request->amount;
        $nature = $request->nature;
        $receiver = User::findOrFail($request->receiver);
        if($user->id != $receiver->id){
            if($user->balance < $amount){
                return response()->json([
                    'status' => 401,
                    'message' => 'Error balance insufficient',
                ]);
            } else {
                $user->debit($amount);
                $receiver->credit($amount);
            }
        } else {
            $receiver->credit($amount);
        }
        Transaction::create([
            'sender_id' => $user->id,
            'receiver_id' => $receiver->id,
            'nature_transaction_id' => $nature,
            'amount' => $amount,
        ]);
        return response()->json([
            'status' => 200,
            'message' => 'Deposit successfully',
        ]);
    }

    public function transfer(Request $request, $id, $account){
        $user = User::where('id', $id)->where('account', $account)->first();
        $amount = $request->amount;
        $nature = $request->nature;
        $receiver = User::findOrFail($request->receiver);
        if($user->balance < $amount){
            return response()->json([
                'status' => 401,
                'message' => 'Error balance insufficient',
            ]);
        } else {
            $user->debit($amount);
            $receiver->credit($amount);
        }
        Transaction::create([
            'sender_id' => $user->id,
            'receiver_id' => $receiver->id,
            'nature_transaction_id' => $nature,
            'amount' => $amount,
        ]);
        return response()->json([
            'status' => 200,
            'message' => 'Transfer successfully',
        ]);
    }

    public function users(){
        $user = User::all();
        return response()->json([
            'status' => 200,
            'user' => $user,
        ]);
    }
}
