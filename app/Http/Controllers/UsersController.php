<?php

namespace App\Http\Controllers;
use App\Models\User;
class UsersController extends Controller
{
    public function users()
    {
        $result = User::all();
        return view('users', ['users' => $result]);
    }
    public function users_admin($userid = null)
    {
        if ($userid) {
            $currentUser = User::findOrFail($userid);
            $currentUser->role = "admin";
            $currentUser->save();

            return back()->with('success', 'The user is now an administrator.');
        }

        abort(403, "Veuillez fournir un ID d utilisateur dans l URL.");
    }
    public function users_client($userid = null)
    {
        if ($userid) {
            $currentUser = User::findOrFail($userid);
            $currentUser->role = "";
            $currentUser->save();

            return back()->with('success', 'The user is now a client.');

        }
        abort(403, "Please provide a user ID in the URL.");

    }
}
