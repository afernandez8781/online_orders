<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Product;
use App\Category;
use App\ProductImage;

class ProductController extends Controller
{
    public function index()
    {
    	$products = Product::paginate(10);
    	return view('admin.products.index')->with(compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->where('status', 'activated')->get();
    	return view('admin.products.create')->with(compact('categories'));
    }

    public function store(Request $request)
    {
    	$rules = [
    		'name' => 'required|min:3',
    		'description' => 'required|max:200',
    		'price' => 'required|numeric|min:0'
    	];
    	$this->validate($request, $rules);

    	$product = new Product();
    	$product->name = $request->input('name');
    	$product->description = $request->input('description');
    	$product->price = $request->input('price');
    	$product->long_description = $request->input('long_description');
        $product->category_id = $request->category_id;
        if ($product->category_id == 0)
        {
            $product->category_id = null;
        }
        // dd($product);
    	$product->save();

    	return redirect('/admin/products');
    }

    public function edit($id)
    {
        $categories = Category::orderBy('name')->where('status', 'activated')->get();
    	$product = Product::find($id);
    	// dd($product);
    	return view('admin.products.edit')->with(compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
    	$rules = [
    		'name' => 'required|min:3',
    		'description' => 'required|max:200',
    		'price' => 'required|numeric|min:0',
    		'long_description' => 'required'
    	];
    	$this->validate($request, $rules);

    	$product = Product::find($id);
    	$product->name = $request->input('name');
    	$product->description = $request->input('description');
    	$product->price = $request->input('price');
    	$product->long_description = $request->input('long_description');
        $product->category_id = $request->category_id;
        if ($product->category_id == 0)
        {
            $product->category_id = null;
        }
    	$product->save();

    	return redirect('/admin/products');
    }

    public function destroy($id)
    {
    	// cartDtail::where('product_id', $id)->delete();
    	ProductImage::where('product_id', $id)->delete();

    	$product = Product::find($id);
    	$product->delete();

    	return back();
    }
}
