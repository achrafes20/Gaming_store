<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
class UsersController extends Controller
{
    public function users(){
         $result=User::all();
        return view('users',['users'=>$result]);
    }
    public function users_admin($userid = null)
{
    if ($userid) {
        $currentUser = User::findOrFail($userid);
        $currentUser->role = "admin";
        $currentUser->save();

        return back()->with('success', 'L utilisateur est maintenant administrateur.');
    }

    abort(403, "Veuillez fournir un ID d utilisateur dans l URL.");
}

public function users_client($userid = null)
{
    if ($userid) {
        $currentUser = User::findOrFail($userid);
        $currentUser->role = ""; // Évite les rôles vides
        $currentUser->save();

        return back()->with('success', 'L utilisateur est maintenant client.');
    }

    abort(403, "Veuillez fournir un ID d utilisateur dans l URL.");
}


}
