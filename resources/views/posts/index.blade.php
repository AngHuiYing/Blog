<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
            color: grey;
            margin-bottom: 20px;
        }

        .container {
            width: 100%;
            background-color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* 边框阴影 */
            padding: 20px;
            border-radius: 8px; /* 圆角 */
        }

        /* 网格布局样式 */
        .post-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .post-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* 单个卡片阴影 */
            transition: transform 0.2s ease-in-out;
        }

        .post-card:hover {
            transform: translateY(-10px); /* 悬停时卡片微微上移 */
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }

        .post-card h2 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #333;
        }

        .post-card p {
            font-size: 16px;
            color: #555;
            margin-bottom: 10px;
        }

        .post-images {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .post-images img {
            max-width: 100px;
            height: auto;
            border-radius: 5px;
            transition: transform 0.2s ease-in-out;
        }

        .post-images img:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .no-images {
            color: #888;
            font-style: italic;
        }
    </style>
</head>
<body>
@extends('blog.app')

@section('title', 'Blog Posts')
@section('content')
    <div class="container">
        <h1 class="mb-4"><ins>Blog Posts</ins></h1>

        {{-- 使用网格布局展示博客帖子 --}}
        @if($posts->isNotEmpty())
        <div class="post-grid">
            @foreach($posts as $post)
            <div class="post-card">
                <h2><ins>{{ $post->title }}</ins></h2>
                <p>{{ $post->content }}</p>
                
                <div class="post-images">
                    {{-- 检查是否有图片 --}}
                    @if($post->images->isNotEmpty())
                        @foreach($post->images as $image)
                            <img src="{{ asset($image->image) }}" alt="Post Image">
                        @endforeach
                    @else
                        <p class="no-images">No images available.</p>
                    @endif
                </div>
                <br>
                <br>
                <a href="{{route('edit',$post->id)}}" style="color:grey;">Edit</a>
                <form action="{{route('delete',$post->id)}}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Are you sure you want to delete this blog?')" style="color:grey;">Delete</button>
            </form>
            </div>
            @endforeach
        </div>
        @else
        <p>No posts available.</p>
        @endif
    </div>
@endsection

</body>
</html>
