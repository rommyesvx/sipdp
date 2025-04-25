<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function showIndexUser()
    {
        return view('users/indexUser');
    }
    public function showFaqUser()
    {
        return view('users/faq');
    }
}
