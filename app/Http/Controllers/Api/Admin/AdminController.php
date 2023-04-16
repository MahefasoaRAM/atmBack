<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                'errors' => $validator->messages(),
            ]);
        } else {
            $admin = Admin::where('email', $request->email)->first();
            if(!$admin || !Hash::check($request->password, $admin->password)){
                return response()->json([
                    'status' => 401,
                    'message' => 'Error credentials invalid',
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'name' => $admin->name,
                    'token' => $admin->api_token,
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
