<?php

namespace App\Http\Controllers;

use App\Services\CatalogClient;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function Addcategory()
    {
        return view('addcategory');
    }

    public function storecategory(Request $request, CatalogClient $catalog)
    {
        $request->validate([
            'name' => ['required', 'max:100'],
            'description' => 'required',
            'photo' => $request->id ? 'nullable|image|max:2048' : 'required|image|max:2048',
        ]);

        $data = $request->only('name', 'description');
        $files = array_filter(['photo' => $request->file('photo')]);

        if ($request->id) {
            $catalog->updateCategory($request->id, $data, $files);
        } else {
            $catalog->createCategory($data, $files);
        }

        return redirect('/categoryadmin')->with('success', 'Category saved successfully!');
    }

    public function RemoveCategory($categoryid, CatalogClient $catalog)
    {
        $catalog->deleteCategory($categoryid);

        return back()->with('success', 'Category deleted successfully!');
    }

    public function categoryadmin(CatalogClient $catalog)
    {
        $categories = collect($catalog->categories()['body']);

        return view('categoryadmin', ['category' => $categories]);
    }
}
