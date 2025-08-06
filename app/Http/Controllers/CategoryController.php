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
            'name' => ['required',  'max:100'], //unique:products dans le meme nom dans la DB
            'description' => 'required',
            'photo' => 'image|mimes:jpg,jpeg,png,gif,svg|max:2048',
        ]);
            $newcategory = new Categories();
            $newcategory->name = $request->name;
            $newcategory->description = $request->description;

            $path = '';
            if ($request->has('photo')) {
                $path = $request->photo->move(
                    'uploads',
                    Str::uuid()->toString() . '-' .  $request->photo->getClientOriginalName()
                );
            }

            $newcategory->imagepath = $path;
            $newcategory->save();

         return redirect('/ProductsTable');
    }

    public function RemoveCategory($categoryid = null)
    {
        if ($categoryid != null) {
            $currentcategory = Categories::find($categoryid);
            $currentcategory->delete();
            return redirect('/ProductsTable');
        } else {
            abort(403, "please enter category id in the route");
        }
    }



}
