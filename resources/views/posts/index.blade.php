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
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 8px;
        }

        .post-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .post-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
        }

        .post-card:hover {
            transform: translateY(-10px);
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

        .post-content {
            display: -webkit-box;
            -webkit-line-clamp: 3; /* 限制显示3行 */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: normal;
            position: relative; /* 使容器能够定位 */
        }

        .more-container {
            display: flex; /* 使用 flexbox 使内容和 ...more 在同一行 */
            justify-content: flex-end; /* 右对齐 ...more */
            position: absolute; /* 绝对定位 */
            bottom: 0; /* 靠底部 */
            right: 0; /* 靠右 */
            background-color: white; /* 背景颜色与内容一致 */
            padding-left: 10px; /* 左边距 */
        }

        .toggle-content {
            color: grey;
            cursor: pointer;
            text-decoration: underline;
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

                <!-- 限制content显示3行 -->
                <div class="post-content" id="content-{{ $post->id }}">
                    {{ $post->content }}
                    <!-- 这里添加一个新容器用于放置 ...more -->
                    @if(strlen($post->content) > 150)  <!-- 如果内容超长才显示"more" -->
                        <div class="more-container">
                            <a href="javascript:void(0);" class="toggle-content" data-post-id="{{ $post->id }}">...more</a>
                        </div>
                    @endif
                </div>
                <br>
                <div class="post-images">
                    {{-- 检查是否有图片 --}}
                    @if($post->images->isNotEmpty())
                        @foreach($post->images as $image)
                            <img src="{{ asset($image->image) }}" alt="Post Image">
                        @endforeach
                    <br>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.toggle-content').forEach(function(toggleLink) {
            toggleLink.addEventListener('click', function() {
                var postId = this.getAttribute('data-post-id');
                var contentElement = document.getElementById('content-' + postId);
                
                // 获取当前状态是 "...more" 还是 "Show less"
                if (this.textContent === "...more") {
                    contentElement.style.webkitLineClamp = 'unset'; // 显示所有行
                    this.textContent = "Close";
                } else {
                    contentElement.style.webkitLineClamp = 3; // 限制回3行
                    this.textContent = "...more";
                }
            });
        });
    });
</script>

</body>
</html>
