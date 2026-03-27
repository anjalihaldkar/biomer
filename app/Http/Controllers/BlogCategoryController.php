<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::orderBy('name')->paginate(15);
        return view('blog.blogCategory', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:100', 'unique:blog_categories,name'],
        ], [
            'name.required' => 'Category name is required.',
            'name.min'      => 'Category name must be at least 2 characters.',
            'name.max'      => 'Category name may not exceed 100 characters.',
            'name.unique'   => 'This category name already exists.',
        ]);

        BlogCategory::create(['name' => $request->name]);

        return back()->with('success', 'Category added successfully.');
    }

    public function update(Request $request, BlogCategory $category)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:100', 'unique:blog_categories,name,' . $category->id],
        ], [
            'name.required' => 'Category name is required.',
            'name.min'      => 'Category name must be at least 2 characters.',
            'name.max'      => 'Category name may not exceed 100 characters.',
            'name.unique'   => 'This category name already exists.',
        ]);

        $category->update(['name' => $request->name]);

        return redirect()->route('blog-categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(BlogCategory $category)
    {
        $category->delete();
        return redirect()->route('blog-categories.index')->with('success', 'Category deleted successfully.');
    }
}