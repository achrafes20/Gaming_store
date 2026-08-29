<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sub;
use Illuminate\Http\Request;

class SubController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate(['email' => 'required|email|unique:subs,email']);

        return response()->json(Sub::create($data), 201);
    }
}
