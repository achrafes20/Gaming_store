<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Categories;

class ApiController extends Controller
{
    // ✅ GET: Récupérer tous les produits
    public function getProductsData()
    {
        $products = Product::all();
        return response()->json(['products' => $products]);
    }

    // ✅ POST: Ajouter une nouvelle catégorie
    public function postData(Request $request)
    {
        try {
            $xcategory = new Categories();
            $xcategory->name = $request->name;
            $xcategory->description = $request->description;
            $xcategory->imagepath = $request->imagepath;
            $xcategory->save();

            return response()->json([
                'message' => 'Data submitted successfully'
            ], 201); // 👈 Code 201: Created

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred',
                'details' => $e->getMessage()
            ], 500); // 👈 Code 500: Server Error
        }
    }
}
