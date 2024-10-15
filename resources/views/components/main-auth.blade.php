<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Vite -->
    @vite([
        'resources/css/auth-app.css', 
        'resources/css/form.css',
        'resources/css/theme-switch.css',
        'resources/css/theme-colors.css',
        'resources/js/app.js'])
    
    <!-- Title -->
    <title>@yield('title') | {{ config('app.name') }}</title>
    
</head>
<body>

    <!-- Theme Switch -->
    <div>
        <button id="theme-switch">
            <i class="fa-solid fa-moon fs-5"></i>
            <i class="fa-solid fa-sun fs-5"></i>
        </button>
    </div>

    <!-- Content -->
    <div class="container d-flex align-items-center vh-100">
        <div class="card-sm mx-auto">
            {{ $slot }}
        </div>
    </div>
    

</body>
</html>