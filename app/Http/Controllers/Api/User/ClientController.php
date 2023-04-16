<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'account' => 'required',
            'pin' => 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                'errors' => $validator->messages(),
            ]);
        } else {
            $user = User::where('account', $request->account)->first();
            if(!$user || !Hash::check($request->pin, $user->pin)){
                return response()->json([
                    'status' => 401,
                    'message' => 'Error credentials invalid',
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'Connexion successfully',
                    'id' => $user->id,
                    'name' => $user->name,
                    'account' => $user->account,
                    'token' => $user->api_token,
                ]);
            }
        }
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Deconnexion successfully',
        ]);
    }
}
