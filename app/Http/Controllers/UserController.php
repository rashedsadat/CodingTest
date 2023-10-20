<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'account_type' => 'required|in:business,individual',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->account_type = $request->account_type;
        $user->password = Hash::make($request->password);
        $user->save();

        return back();
    }
}
