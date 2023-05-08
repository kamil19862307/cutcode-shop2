<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', env('APP_NAME'))</title>
    @vite([
        'resources/css/app.css',
        'resources/sass/main.sass',
        'resources/js/app.js',
        ])
</head>
<body>

@yield('content')

</body>
</html>
