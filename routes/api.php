<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\User\ActionController;
use App\Http\Controllers\Api\User\ClientController;
use App\Http\Controllers\Api\Admin\TransactionController;
use App\Http\Controllers\Api\Admin\NatureTransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('admin', [AdminController::class, 'login']);
Route::post('login', [ClientController::class, 'login']);

Route::middleware('auth:api-admin')->group(function(){
    Route::post('adminlogout', [AdminController::class, 'logout']);

    //nature transaction
    Route::post('natureadd', [NatureTransactionController::class, 'naturetransactionadd']);
    Route::get('naturelist', [NatureTransactionController::class, 'naturetransactionlist']);

    //transactions
    Route::get('transactionlist', [TransactionController::class, 'transactionlist']);
    Route::get('transactiondetails/{id}', [TransactionController::class, 'transactiondetails']);

    //user
    Route::post('useradd', [UserController::class, 'useradd']);
    Route::get('userlist', [UserController::class, 'userlist']);
    Route::get('userdetails/{id}', [UserController::class, 'userdetails']);
    Route::get('useredit/{id}', [UserController::class, 'useredit']);
    Route::put('userupdate/{id}', [UserController::class, 'userupdate']);
    Route::delete('userdelete/{id}', [UserController::class, 'userdelete']);
});

Route::middleware('auth:api-user')->group(function(){
    Route::post('logout', [ClientController::class, 'logout']);

    //view own balance
    Route::get('balance/{id}.{account}', [ActionController::class, 'viewbalance']);

    //transactions list
    Route::get('transactionlist/{id}.{account}', [ActionController::class, 'transactionlist']);

    //withdraw
    Route::post('withdraw/{id}.{account}', [ActionController::class, 'withdraw']);

    //deposit
    Route::post('deposit/{id}.{account}', [ActionController::class, 'deposit']);

    //transfer
    Route::post('transfer/{id}.{account}', [ActionController::class, 'transfer']);
    Route::get('users', [ActionController::class, 'users']);
});
