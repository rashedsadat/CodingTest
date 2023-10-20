<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/users', 'UserController@create')->name('users');

Route::post('login', 'LoginController@login')->name('login');

Route::get('/index', 'TransactionController@showTransactions')->name('show.transactions');

Route::get('index', 'DepositController@showDeposits')->name('show.deposit');
Route::post('deposit', 'DepositController@deposit')->name('deposit');

Route::get('index', 'WithdrawalController@showWithdrawal')->name('show.withdrawal');
Route::post('withdrawal', 'WithdrawalController@withdrawal')->name('withdrawal');
