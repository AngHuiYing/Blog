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
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        if ($request->hasFile('image') && $request->file('image') != null) {
            $path = $request->file('image')->store('public/images');
            Image::create([
                'post_id' => $post->id,
                'image' => $path,
            ]);
        }

        return redirect()->route('posts.index');
    }

    public function edit($id){

        $post = Post::findOrFail($id);

        return view('edit', compact('post'));
    }

    public function Update(Request $request, $id){

        $post = Post::findOrFail($id);

        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        //$sql= "UPDATE tasks SET title = '$request=title',"
        $post->update($request->all());

        return redirect()->route('posts.index')->with('success', 'Task updated successfully');
    }

    public function delete($id){

        $post = Post::findOrFail($id);

        //$sql= "DELETE FROM tasks WHERE"
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Task deleted successfully');
    }
}
