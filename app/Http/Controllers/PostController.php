<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    // Handle deleting old images if requested
    if ($request->has('delete_images')) {
        $deleteImages = explode(',', $request->input('delete_images'));
        foreach ($deleteImages as $imageId) {
            $image = Image::find($imageId);
            if ($image) {
                $image->delete();
                Storage::delete('public/images/' . $image->image); // 删除存储的图片文件
            }
        }
    }

    // 已上传图片的路径数组
    $existingImages = $post->images->pluck('image')->map(function ($image) {
        return basename($image); // 取文件名
    })->toArray();

    // 处理新图片
    // 处理新图片
if ($request->hasFile('images')) {
    if (count($request->file('images')) > 10) {
        return back()->withErrors(['images' => 'You can only upload up to 10 images.']);
    }

    // 已上传图片的路径数组
    $existingImages = $post->images->pluck('image')->map(function ($image) {
        return basename($image); // 取文件名
    })->toArray();

    foreach ($request->file('images') as $image) {
        $imageName = time() . '-' . $image->getClientOriginalName();

        // 检查是否已经存在
        if (in_array($image->getClientOriginalName(), $existingImages)) {
            return back()->withErrors(['images' => 'One or more images are already uploaded.']);
        }

        // 存储图片
        $image->move(public_path('Images'), $imageName);

        // 保存新的图片路径
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
