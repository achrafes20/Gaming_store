<?php

namespace App\Http\Controllers;

use App\Services\UsersClient;

class UsersController extends Controller
{
    public function users(UsersClient $users)
    {
        $result = collect($users->users()['body']);

        return view('users', ['users' => $result]);
    }

    public function users_admin($userid, UsersClient $users)
    {
        $users->promote($userid);

        return back()->with('success', 'The user is now an administrator.');
    }

    public function users_client($userid, UsersClient $users)
    {
        $users->demote($userid);

        return back()->with('success', 'The user is now a client.');
    }
}
