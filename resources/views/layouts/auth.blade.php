<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
</head>

<body>
    <!-- Header Destop -->
    <header class="flex border-b border-gray-200">
        <div class="container mx-auto py-4 px-6">
            <h1 class="text-xl font-semibold text-gray-800">KopsisApp</h1>
        </div>
    </header>
    @yield('content')
</body>

</html>