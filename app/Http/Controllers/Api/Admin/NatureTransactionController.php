<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Models\NatureTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class NatureTransactionController extends Controller
{
    public function naturetransactionlist(){
        $nature = NatureTransaction::all();
        return response()->json([
            'status' => 200,
            'nature' => $nature,
        ]);
    }

    public function naturetransactionadd(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'errors' => $validator->messages(),
            ]);
        } else {
            NatureTransaction::create([
                'name' => $request->name,
            ]);
            return response()->json([
                'status' => 200,
                'message' => 'Nature transaction added'
            ]);
        }
    }
}
