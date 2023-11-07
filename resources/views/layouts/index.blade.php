<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <nav class="nav container">
        <a class="nav-link" href="{{ route('index') }}">Create</a>
        <a class="nav-link" href="{{ route('post.index') }}">Posts</a>
    </nav>
    @yield('content')
</body>

</html>