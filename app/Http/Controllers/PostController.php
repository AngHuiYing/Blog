<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Image;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // 显示所有博客文章
    public function index(Request $request)
    {
        $posts = Post::with('images')->get(); // 延迟加载 images 关系
        return view('posts.index', compact('posts'));
    }

    // 显示创建博客文章的页面
    public function create()
    {
        return view('posts.create');  // 返回创建博客文章的视图
    }

    // 处理博客文章的提交
    public function store(Request $request)
{
    // Validate the request
    $request->validate([
        'title' => 'required',
        'content' => 'required',
        'images' => 'nullable|array',  // Allow multiple images
        'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',  // Validate each image
    ]);

    // Create the post
    $post = Post::create([
        'title' => $request->title,
        'content' => $request->content,
    ]);

    // Handle image uploads
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            // Generate a unique name for each image
            $imageName = time() . '-' . $image->getClientOriginalName();
            
            // Move the image to the public/Images directory
            $image->move(public_path('Images'), $imageName);
            
            // Save the image path in the database
            Image::create([
                'post_id' => $post->id,
                'image' => 'Images/' . $imageName,  // Path relative to the public folder
            ]);
        }
    }

    // Redirect back to the posts index page
    return redirect()->route('posts.index');
}

    public function edit($id){

        $post = Post::findOrFail($id);

        return view('edit', compact('post'));
    }

    public function update(Request $request, $id)
{
    // Find the post to be updated
    $post = Post::findOrFail($id);

    // Validate the request data
    $request->validate([
        'title' => 'required',
        'content' => 'required',
        'images' => 'nullable|array',  // Allow multiple images
        'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',  // Validate each image
    ]);

    // Update title and content
    $post->update([
        'title' => $request->title,
        'content' => $request->content,
    ]);

    // Handle image uploads
    if ($request->hasFile('images')) {
        // Delete old images (optional)
        foreach ($post->images as $oldImage) {
            if (file_exists(public_path($oldImage->image))) {
                unlink(public_path($oldImage->image)); // Delete the old image
            }
            $oldImage->delete(); // Delete the old image record from the database
        }

        // Upload new images
        foreach ($request->file('images') as $image) {
            $imageName = time() . '-' . $image->getClientOriginalName();
            $image->move(public_path('Images'), $imageName);

            // Save the new image path in the database
            Image::create([
                'post_id' => $post->id,
                'image' => 'Images/' . $imageName,
            ]);
        }
    }

    return redirect()->route('posts.index')->with('success', 'Post updated successfully');
}

    public function delete($id){

        $post = Post::findOrFail($id);

        //$sql= "DELETE FROM tasks WHERE"
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Task deleted successfully');
    }
}
