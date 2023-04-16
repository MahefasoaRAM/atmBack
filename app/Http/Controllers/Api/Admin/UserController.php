<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function userlist(){
        $user = User::all();
        return response()->json([
            'status' => 200,
            'user' => $user,
        ]);
    }

    public function useradd(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|regex:/(0)[0-9]{9}/',
            'account' => 'required|unique:users,account',
            'pin' => 'required|min:4|max:4',
            'balance' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'errors' => $validator->messages(),
            ]);
        } else {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'account' => $request->account,
                'pin' => Hash::make($request->pin),
                'balance' => $request->balance,
            ]);
            return response()->json([
                'status' => 200,
                'message' => 'User added'
            ]);
        }
    }

    public function useredit($id){
        $user = User::find($id);
        if($user){
            return response()->json([
                'status' => 200,
                'user' => $user,
            ]);
        }
    }

    public function userdetails($id){
        $user = User::find($id);
        if($user){
            return response()->json([
                'status' => 200,
                'user' => $user,
            ]);
        }
    }

    public function userupdate(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'phone' => 'required|regex:/(0)[0-9]{9}/',
            'account' => 'required|unique:users,account,'.$id,
            'pin' => 'min:4|max:4',
            'balance' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'errors' => $validator->messages(),
            ]);
        } else {
            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['phone'] = $request->phone;
            $data['account'] = $request->account;
            $data['balance'] = $request->balance;
            if($request->has('pin')){
                $data['pin'] = Hash::make($request->pin);
            }
            $user = User::find($id);
            $user->update($data);
            return response()->json([
                'status' => 200,
                'message' => 'User updated',
            ]);
        }
    }

    public function userdelete($id){
        $user = User::find($id);
        $user->delete();
        return response()->json([
            'status' => 200,
            'message' => 'User deleted',
        ]);
    }
}
