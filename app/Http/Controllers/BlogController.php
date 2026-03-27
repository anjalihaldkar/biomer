<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function blog()
    {
        $blogs = Blog::with('category')->latest()->get();
        return view('blog/blog', compact('blogs'));
    }

    public function addBlog()
    {
        $categories = BlogCategory::orderBy('name')->get();
        $blogs      = Blog::with('category')->latest()->take(5)->get();
        return view('blog/addBlog', compact('categories', 'blogs'));
    }

    public function storeBlog(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:blog_categories,id',
            'description' => 'required|string',
            'tags'        => 'nullable|string|max:255',
            'status'      => 'required|in:draft,published',
            'thumbnail'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only(['title', 'category_id', 'description', 'tags', 'status']);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('blogs', 'public');
        }

        Blog::create($data);

        return redirect()->route('blog')->with('success', 'Blog post created successfully.');
    }

    public function editBlog(Blog $blog)
    {
        $categories = BlogCategory::orderBy('name')->get();
        $blogs      = Blog::with('category')->latest()->take(5)->get();
        return view('blog/addBlog', compact('categories', 'blogs', 'blog'));
    }

    public function updateBlog(Request $request, Blog $blog)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:blog_categories,id',
            'description' => 'required|string',
            'tags'        => 'nullable|string|max:255',
            'status'      => 'required|in:draft,published',
            'thumbnail'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only(['title', 'category_id', 'description', 'tags', 'status']);

        if ($request->hasFile('thumbnail')) {
            if ($blog->thumbnail) Storage::disk('public')->delete($blog->thumbnail);
            $data['thumbnail'] = $request->file('thumbnail')->store('blogs', 'public');
        }

        $blog->update($data);

        return redirect()->route('blog')->with('success', 'Blog post updated successfully.');
    }

    public function destroyBlog(Blog $blog)
    {
        if ($blog->thumbnail) Storage::disk('public')->delete($blog->thumbnail);
        $blog->delete();
        return redirect()->route('blog')->with('success', 'Blog post deleted successfully.');
    }

    public function blogDetails(Blog $blog)
    {
        $recentBlogs = Blog::with('category')
                           ->where('id', '!=', $blog->id)
                           ->latest()
                           ->take(5)
                           ->get();

        $categories = BlogCategory::withCount('blogs')->orderBy('name')->get();

        return view('blog/blogDetails', compact('blog', 'recentBlogs', 'categories'));
    }
}