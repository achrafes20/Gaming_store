<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function Addcategory()
    {
        return view('addcategory');
    }

    public function storecategory(Request $request)
{
    $request->validate([
        'name' => ['required', 'max:100'],
        'description' => 'required',
        'photo' => $request->id ? 'nullable|image' : 'required|image',
    ]);

    if ($request->id) {
        // Modification
        $category = Categories::findOrFail($request->id);
    } else {
        // Création
        $category = new Categories();
    }

    $category->name = $request->name;
    $category->description = $request->description;

    if ($request->hasFile('photo')) {
        $filename = Str::uuid()->toString() . '-' . $request->photo->getClientOriginalName();
        $request->photo->move(public_path('uploads'), $filename);
        $category->imagepath = 'uploads/' . $filename;
    }

    $category->save();

    return redirect('/ProductsTable');
}


    public function RemoveCategory($categoryid = null)
    {
        if ($categoryid != null) {
            $currentcategory = Categories::find($categoryid);
            $currentcategory->delete();
            return redirect('/');
        } else {
            abort(403, "please enter category id in the route");
        }
    }
    public function categoryadmin(){
        $categories = Categories::all();
        return view('categoryadmin', ['category'=>$categories]);
    }

}
