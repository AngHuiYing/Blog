
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Blog</title>
    <style>
        /* 不再使用全局选择器，限定表单样式范围 */
        .blog-form * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        h1 {
            color: grey;
            text-align: left;
            margin-bottom: 20px;
            padding-right: 10px;
        }

        /* 表单容器样式 */
        .blog-form {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        /* 输入框样式 */
        .blog-form div {
            margin-bottom: 15px;
        }

        .blog-form label {
            font-size: 16px;
            font-weight: 500;
            color: #333;
            display: block;
            margin-bottom: 5px;
        }

        .blog-form input[type="text"], .blog-form textarea, .blog-form input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .blog-form input[type="text"]:focus, .blog-form textarea:focus, .blog-form input[type="file"]:focus {
            border-color: #16a085; /* 聚焦时边框颜色变化 */
        }

        .blog-form textarea {
            resize: vertical;
            min-height: 150px;
        }

        .blog-form input[type="file"] {
            padding: 5px;
        }

        /* 错误信息样式 */
        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }

        /* 提交按钮样式 */
        .blog-form button {
            background-color: white;
            color: grey;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .blog-form button:hover {
            background-color: #f1f1f1;
            transform: scale(1.05); /* 鼠标悬停时按钮稍微放大 */
        }

        /* 响应式设计 */
        @media (max-width: 768px) {
            .blog-form {
                width: 90%;
                padding: 20px;
            }

            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

@extends('blog.app')

@section('title', 'Create Blog')

@section('content')
<br>
<br>
    <h1>  Create a New Blog Post</h1>

    {{-- 显示验证错误 --}}
    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- 创建博客文章的表单 --}}
    <div class="center">
    <form class="blog-form" action="{{ route('update',$post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf  {{-- 防止CSRF攻击 --}}
        @method('PUT')
        <div>
            <label for="title">Title</label>
            <input type="text" name="title" id="title" value="{{$post->title}}" required>
        </div>

        <div>
            <label for="content">Content</label>
            <textarea name="content" id="content" rows="5" required>{{$post->content}}</textarea>
        </div>

        <div>
            <label for="image">Upload Image</label>
            <input type="file" name="image" id="image" value="{{$post->image}}" multiple>
        </div>

        <div>
            <button type="submit">Update Blog</button>
        </div>
    </form>
    </div>
@endsection

</body>
</html>
