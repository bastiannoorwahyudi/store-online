<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Category;
use App\Product;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        // menampilkan data category dengan pagination sebanyak 8 item
        $products =Product::with(['galleries'])->paginate(8);

        return view ('pages.category', compact('categories', 'products'));
    }

    public function detail(Request $request, $slug)
    {
        $categories = Category::all();
        $category = Category::where('slug', $slug)->firstOrFail();
        // menampilkan data category dengan pagination sebanyak 8 item
        $products =Product::with(['galleries'])->where('categories_id', $category->id)->paginate(8);

        return view ('pages.category', compact('categories', 'products'));
    }

}
