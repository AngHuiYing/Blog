
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Blog</title>
    <style>
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

        .blog-form {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

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
            border-color: #16a085;
        }

        .blog-form textarea {
            resize: vertical;
            min-height: 150px;
        }

        .blog-form input[type="file"] {
            padding: 5px;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }

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
            transform: scale(1.05);
        }

        .existing-images {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        .existing-images img {
            width: 100px;
            height: 100px;
            position: relative;
        }

        .delete-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            font-size: 12px;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>

@extends('blog.app')

@section('title', 'Update Blog')

@section('content')
<br>
<br>
    <h1>Update Blog Post</h1>

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

    {{-- 更新博客文章的表单 --}}
    <div class="center">
<form class="blog-form" action="{{ route('update', $post->id) }}" method="POST" enctype="multipart/form-data">
    @csrf  {{-- 防止CSRF攻击 --}}
    @method('PUT')

    <div>
        <label for="title">Title</label>
        <input type="text" name="title" id="title" value="{{ $post->title }}" required>
    </div>

    <div>
    <label for="content">Content</label>
    <textarea name="content" id="content" rows="5" maxlength="200" required>{{ old('content', $post->content) }}</textarea>
    <p>Remaining characters: <span id="remaining-chars">200</span></p>
</div>


    <div>
        <label for="image">Upload Image</label>
        <input type="file" name="images[]" id="image" multiple>
        <p class="error" id="image-error" style="display:none;">You can only upload up to 10 images.</p>
    </div>

    {{-- 显示已上传的图片 --}}
    @if($post->images->isNotEmpty())
        <div class="existing-images">
            @foreach($post->images as $image)
                <div class="image-container" style="position:relative;">
                    <img src="{{ asset($image->image) }}" alt="Existing Image">
                    <button type="button" class="delete-btn" data-id="{{ $image->id }}">x</button>
                </div>
            @endforeach
        </div>
    @endif

    {{-- 隐藏字段，用于存储要删除的图片ID --}}
    <input type="hidden" name="delete_images" id="delete_images" value="">

    <div>
        <button type="submit">Update Blog</button>
    </div>
</form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('image');
        const errorElement = document.getElementById('image-error');
        
        imageInput.addEventListener('change', function() {
            console.log("Image input changed");
            console.log("Selected files:", imageInput.files);
            // 清空错误信息
            errorElement.style.display = 'none';
            errorElement.textContent = '';
            
            // 限制最多选择10张图片
            if (imageInput.files.length > 10) {
                errorElement.textContent = 'You can only upload up to 10 images.';
                errorElement.style.display = 'block';
                imageInput.value = ''; // 清空输入框
                return; // 退出函数
            }

            let duplicateFound = false;

            // 获取已上传的图片路径（可以从后端渲染时传递）
            var existingImages = @json($post->images->pluck('image'));
            let existingImageNames = existingImages.map(name => name.toLowerCase()); // 所有已存在文件名转为小写

            // 检查每个新选择的文件
            for (let i = 0; i < imageInput.files.length; i++) {
                let selectedFile = imageInput.files[i];
                let selectedFileName = selectedFile.name.toLowerCase(); // 将文件名转为小写

                console.log("Checking file:", selectedFileName);
                
                // 检查是否已上传
                if (existingImageNames.includes(selectedFileName)) {
                    duplicateFound = true;
                    console.log("Duplicate found:", selectedFileName);
                    break;
                }
            }

            if (duplicateFound) {
                errorElement.textContent = 'You cannot select an image that has already been uploaded.';
                errorElement.style.display = 'block';
                imageInput.value = ''; // 清空输入框
            }
        });

        // 处理删除按钮
        const deleteBtns = document.querySelectorAll('.delete-btn');
        const deleteImagesInput = document.getElementById('delete_images');
        let deleteImageIds = [];

        deleteBtns.forEach(button => {
            button.addEventListener('click', function() {
                const imageId = this.getAttribute('data-id');

                // 将图片ID添加到数组中
                deleteImageIds.push(imageId);
                deleteImagesInput.value = deleteImageIds.join(',');

                // 移除图片显示
                this.parentElement.remove();
            });
        });
    });

    var contentTextarea = document.getElementById('content');
    var remainingChars = document.getElementById('remaining-chars');

    contentTextarea.addEventListener('input', function() {
        var currentLength = contentTextarea.value.length;
        remainingChars.textContent = 200 - currentLength;
    });

</script>

@endsection

</body>
</html>
