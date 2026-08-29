<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserAdminController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function promote(User $user)
    {
        $user->update(['role' => 'admin']);

        return $user;
    }

    public function demote(User $user)
    {
        $user->update(['role' => 'client']);

        return $user;
    }
}
