<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\ProductRequest;
use App\ProductGallery;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class DashboardProductsController extends Controller
{
    public function index()
    {
        $products = Product::with(['galleries', 'category'])
                            ->where('users_id', Auth::user()->id)
                            ->get();

        return view ('pages.dashboard-products', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view ('pages.dashboard-products-create', compact('categories'));
    }

    public function details(Request $request, $id)
    {
        // ambil semua data dari table-table berikut berdasarkan ID
        $product = Product::with(['galleries', 'user', 'category'])->findOrFail($id);
        $categories = Category::all();

        return view ('pages.dashboard-products-details', compact('product', 'categories'));
    }

    public function uploadGallery(Request $request)
    {
        $data_product = $request->all();
        $data_product['photos'] = $request->file('photos')->store('assets/product','public');
        ProductGallery::create($data_product);

        return redirect()->route('dashboard-products-details', $request->products_id);
    }

    public function deleteGallery(Request $request, $id)
    {
        $item = ProductGallery::findOrFail($id);
        $item->delete();

        return redirect()->route('dashboard-products-details', $item->products_id);
    }

    public function store(ProductRequest $request)
    {
        // simpan data ke table PRODUCT
        $data_product = $request->all();
        $data_product['slug'] = Str::slug($request->name);
        $product = Product::create($data_product);

        // simpan data ke table GALLERI
        $gallery = [
            'products_id' => $product->id,
            'photos' => $request->file('photo')->store('assets/product', 'public')
        ];
        ProductGallery::create($gallery);

        return redirect()->route('dashboard-products');
    }

    public function update(ProductRequest $request, $id)
    {
        $data_product = $request->all();
        $item = Product::findOrFail($id);

        $data_product['slug'] = Str::slug($request->name);

        $item->update($data_product);

        return redirect()->route('dashboard-products');
    }
}
