<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Category;

class CategoryController extends Controller
{
    public function index()
    {
    	$categories = Category::orderBy('name')->where('status', 'activated')->paginate(10);
    	return view('admin.categories.index')->with(compact('categories'));
    }

    public function create()
    {
    	return view('admin.categories.create');
    }

    public function store(Request $request)
    {
    	$rules = [
    		'name' => 'required|min:3',
    		'description' => 'max:250'
    	];
    	$this->validate($request, $rules);

    	// registrar en la db
    	Category::create($request->all()); // mass assignment

    	return redirect('/admin/categories');
    }

    public function edit(Category $category)
    {
    	return view('admin.categories.edit')->with(compact('category'));
    }

    public function update(Request $request, Category $category)
    {
    	$rules = [
    		'name' => 'required|min:3',
    		'description' => 'max:200'
    	];
    	$this->validate($request, $rules);

    	$category->update($request->all());

    	return redirect('/admin/categories');
    }

    public function destroy(Category $category)
    {
        $category->status = 'disabled';
        $category->save();
    	// $category->status('disabled');
    	return back();
    }
}
