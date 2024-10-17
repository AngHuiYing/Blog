<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <style>
        /* 全局样式 */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to bottom right, white, #fff); /* 渐变背景 */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header 和导航栏样式 */
        header {
            background-color: white; /* 灰色背景 */
            padding: 10px 0; /* 减少 padding 使 header 更小 */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* 添加阴影 */
        }

        nav {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Logo 样式 */
        .logo {
            font-size: 20px;
            font-weight: bold;
            color: grey;
            letter-spacing: 1px;
        }

        /* 导航列表样式 */
        ul {
            list-style: none;
            display: flex;
            gap: 15px; /* 缩小选项之间的间距 */
        }

        ul li a {
            text-decoration: none;
            color: grey;
            font-size: 14px;
            font-weight: 500;
            padding: 8px 15px; /* 调整 padding 使按钮更小 */
            border-radius: 20px; /* 调整为按钮样式 */
            background-color: ; /* 按钮背景色 */
            transition: background-color 0.3s ease, transform 0.2s ease; /* 增加平滑的过渡效果 */
        }

        ul li a:hover {
            background-color: ; /* 悬停时背景变为亮绿色 */
            transform: scale(1.05); /* 悬停时稍微放大 */
        }

        /* 响应式样式 */
        @media (max-width: 768px) {
            nav {
                flex-direction: column;
            }

            ul {
                flex-direction: column;
                gap: 10px; /* 小屏幕上选项之间的间距 */
            }

            ul li a {
                font-size: 14px;
                padding: 8px 16px;
                text-align: center;
            }
        }
    </style>
</head>
<body>

<header>
    <nav>
        <div class="logo">My Blog</div> <!-- Logo 标志 -->
        <ul>
            <li><a href="{{ route('posts.index') }}">Home</a></li> <!-- 主页 -->
            <li><a href="{{ route('posts.create') }}">Create Blog</a></li> <!-- 创建博客 -->
        </ul>
    </nav>
</header>

</body>
</html>
