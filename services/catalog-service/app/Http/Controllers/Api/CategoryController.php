<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        return Categories::all();
    }

    public function show(Categories $category)
    {
        return $category->load('products');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'max:100'],
            'description' => 'required',
            'photo' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
        ]);

        $filename = Str::uuid()->toString().'-'.$request->photo->getClientOriginalName();
        $request->photo->move(public_path('uploads'), $filename);

        $category = Categories::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'imagepath' => 'uploads/'.$filename,
        ]);

        return response()->json($category, 201);
    }

    public function update(Request $request, Categories $category)
    {
        $data = $request->validate([
            'name' => ['required', 'max:100'],
            'description' => 'required',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $filename = Str::uuid()->toString().'-'.$request->photo->getClientOriginalName();
            $request->photo->move(public_path('uploads'), $filename);
            $data['imagepath'] = 'uploads/'.$filename;
        }

        $category->update($data);

        return $category;
    }

    public function destroy(Categories $category)
    {
        $category->delete();

        return response()->noContent();
    }
}
