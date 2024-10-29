<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Vite -->
     @vite([
        'resources/css/app.css',
        'resources/css/auth-app.css',
        'resources/css/form.css',
        'resources/css/modal.css', 
        'resources/css/theme-switch.css',
        'resources/css/theme-colors.css',
        'resources/js/app.js'])

        <style>
            button {
                padding: 8px 15px 8px 15px !important;
                border-radius: 100px !important;
                font-weight: 500 !important;
                background: var(--profile-fill-hover) !important;
                border: 0 !important;
                margin-bottom: 10px !important;
            }
        </style>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>404 | {{ config('app.name') }}</title>
</head>
<body>
    @php
        $appUrl = config('app.url'); // Get APP_URL from config
    @endphp

    <div class="container d-flex min-vh-100 justify-content-center align-items-center">
        {{-- <div class="image-text">
            <img src="{{ URL('images/People360 Logo Light.png') }}" alt="logo">
            <img src="{{ URL('images/People360 Logo Dark.png') }}" alt="logo">
        </div> --}}
        <div class="text-center">
            <h1 class="fw-semibold mb-3">Page Not Found</h1>
            <p class="m-0">The page you are looking was moved,</p>
            <p class="m-0">removed, renamed, or might never exist!</p>
            {{-- <button></button> --}}
            <button class="btn btn-primary border-0 text-white w-100 mt-2" style="width: 110px !important" onclick="window.location.href='{{ $appUrl }}'">Go to home</button>
        </div>

    </div>

</body>
</html>