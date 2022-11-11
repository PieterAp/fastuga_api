<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function getUser(User $user)
    {
        return $user;
    }

    public function getUsers()
    {

        $users = User::all();
        return $users;
    }
}
