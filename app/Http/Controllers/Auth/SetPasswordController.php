<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SetPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function reset()
    {
        return view('auth/reset');
    }

    public function set(Request $request)
    {
        $user = Auth::user();
        $user->update(['password' => Hash::make($request->password)]);

        return redirect('/');
    }
}
