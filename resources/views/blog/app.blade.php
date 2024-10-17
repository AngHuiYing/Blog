<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield("title") | Blog</title>
</head>
<body>
<header>
        @include("layouts.header")
    </header>

    <main>
        @yield("content")
    </main>

    <footer>
        @include("layouts.footer")
    </footer>
</body>
</html>