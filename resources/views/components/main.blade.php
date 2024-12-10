<!DOCTYPE html>
<html lang="en">
<head>

    <!-- Vite -->
    @vite([
        'resources/css/app.css',
        // 'resources/css/main/auth-app.css', 
        // 'resources/css/main/theme-colors.css', 
        // 'resources/css/main/theme-switch.css', 
        'resources/js/app.js'
        ])
        
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    
    <!-- Title -->
    <title>@yield('title', 'Dashboard') | {{ config('app.name') }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}

    <!-- Tabler Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" rel="stylesheet">

    <!-- FullCalendar CSS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>

    <!-- Bootstrap Datatable -->
    <link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>

    
</head>
<body id="main-layout">

    <div class="wrapper">

        <!-- Theme Switch -->
        <div>
            <button id="theme-switch">
                <i class="fa-solid fa-moon fs-5"></i>
                {{-- {!! file_get_contents(public_path('images/svg-icon/moon.svg')) !!} --}}
                {{-- {!! file_get_contents(public_path('images/svg-icon/sun.svg')) !!} --}}
                <i class="fa-solid fa-sun fs-5"></i>
            </button>
        </div>

        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar">
            <div class="h-100">

                <!-- Header -->
                <header class="sidebar-logo mb-2">
                    <div class="image-text">
                        <div class="text header-text">
                            <img src="{{ URL('images/People360 Logo Light.png') }}" alt="logo">
                            <img src="{{ URL('images/People360 Logo Dark.png') }}" alt="logo">
                            {{-- <span class="company-name">PEOPLE360</span> --}}
                            {{-- <span class="company-name2">Consulting Corporation</span> --}}
                        </div>
                    </div>

                </header>

                <!-- Profile -->
                <div class="profile">
                    <div class="profile-item">


                        <!-- Profile Description -->
                        <div class="profile-main">
                            <span class="image">
                                <img src="{{ URL('images/profile-img.jpeg') }}" alt="logo">
                            </span>
            
                            <div class="profile-text w-100">
                                <span class="username fw-semibold" id="sidebar-username">{{ Str::ucfirst(auth()->user()->username) }}</span>
                                <span class="role fw-normal" id="sidebar-role">{{ Auth::user()->role->role_name }}</span>
                                <span class="role fw-normal fst-italic" id="sidebar-department">{{ Auth::user()->department->department_name }} Department</span>
                            </div>
                            <button class="collapsed border-0" data-bs-toggle="collapse" data-bs-target="#profile-list">
                                <i class="fa-solid fa-bars-staggered"></i>
                            </button>
                        </div>

                        <!-- Profile Menu -->
                        <div id="profile-list" class="profile-list sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <ul>
                                <li class="mb-3">
                                    <a href="{{ route('profile.update') }}">
                                        {{-- <i class="fa-regular fa-user"></i> --}}
                                        <i class="fs-5 ti ti-user"></i>
                                        My Account
                                    </a>
                                </li>
                                <li class="mb-3">
                                    <a href="account-settings">
                                        {{-- <i class="fa-solid fa-gear"></i> --}}
                                        <i class="fs-5 ti ti-settings"></i>
                                        Settings
                                    </a>
                                </li>
                                <li>
                                    <form id="myForm" action="{{ route('logout') }}" method="post">
                                        @csrf

                                        <a onclick="document.getElementById('myForm').submit(); return false;">
                                            {{-- <i class="fa-solid fa-power-off"></i> --}}
                                            <i class="fs-5 ti ti-power"></i>
                                            Logout
                                        </a>

                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
             
                <!-- Sidebar Navigation -->
                <div class="sidebar-nav">
                    <ul class="m-0 p-0">
                        <li class="sidebar-header2 fw-semibold">
                            NAVIGATION
                        </li>
                        <li class="sidebar-item2 mb-1">
                            <a href="{{ route('dashboard') }}">
                                {{-- <i class="fa-solid fa-house"></i> --}}
                                {!! file_get_contents(public_path('images/svg-icon/dashboard.svg')) !!}
                                <span class="sidebar-text">Dashboard</span>
                            </a>
                        </li>
                                        
                        <li class="sidebar-item2 mb-1">
                            <a href="{{ route('overview') }}">
                                {{-- <i class="fa-solid fa-square-poll-vertical"></i> --}}
                                {!! file_get_contents(public_path('images/svg-icon/compliance-due.svg')) !!}
                                <span class="sidebar-text">Compliance Due</span>
                            </a>
                        </li>

                        <li class="sidebar-item2 mb-1">
                            <a href="{{ route('projections') }}">
                                {{-- <i class="fa-solid fa-chart-line"></i> --}}
                                {!! file_get_contents(public_path('images/svg-icon/projection.svg')) !!}
                                <span class="sidebar-text">Compliance Projection</span>
                            </a>
                        </li>

                        <li class="sidebar-item2 mb-1">
                            <a href="{{ route('calendar') }}">
                                {{-- <i class="fa-solid fa-calendar-days"></i> --}}
                                {!! file_get_contents(public_path('images/svg-icon/calendar.svg')) !!}
                                <span class="sidebar-text">Calendar</span>
                            </a>
                        </li>
                        <li class="sidebar-item2 mb-1">
                            <a href="{{ route('compliances.index') }}">
                                {{-- <i class="fa-solid fa-file-circle-plus"></i> --}}
                                {!! file_get_contents(public_path('images/svg-icon/reference.svg')) !!}
                                <span class="sidebar-text">Compliance Reference</span>
                            </a>
                        </li>
                        <li class="sidebar-item2 mb-1">
                            <a href="{{ route('complianceRequests') }}">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        {{-- <i class="fa-solid fa-file-import"></i> --}}
                                        {!! file_get_contents(public_path('images/svg-icon/request.svg')) !!}
                                        <span class="sidebar-text">Compliance Request</span>
                                    </div>
                                    <div>
                                        @if (!empty($totalRequestsCount))
                                            <span class="circle-badge" id="requestBadge">{{ $totalRequestsCount }}</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </li>
                        {{-- <li class="sidebar-item2 mb-1">
                            <a href="{{ route('compliance-management.index') }}">
                                <i class="fa-solid fa-folder-open"></i>
                                <span>Compliance Management</span>
                            </a>
                        </li> --}}
                        <li class="sidebar-item2 mb-1">
                            <a href="{{ route('logs.data') }}">
                                {{-- <i class="fa-solid fa-book"></i> --}}
                                {!! file_get_contents(public_path('images/svg-icon/logs.svg')) !!}
                                <span class="sidebar-text">Logs</span>
                            </a>
                        </li>
                        {{-- <li class="sidebar-item2 mb-1">
                            <a href="{{ route('logs.sample') }}">
                                <i class="fa-solid fa-book"></i>
                                <span>Logs Sample</span>
                            </a>
                        </li> --}}

                        {{-- <li class="sidebar-item2 mb-1">
                            <a href="accounts">
                                <i class="fa-solid fa-users"></i>
                                <span>Accounts</span>
                            </a>
                        </li> --}}

                  
                    
                    </ul>
                </div>

                <!-- Sidebar Navigation -->
                {{-- <ul class="sidebar-nav">
                    <li class="sidebar-header">
                        Tools & Components
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <i class="fa-solid fa-list pe-2"></i>
                            Profile
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#pages"
                            aria-expanded="false" aria-controls="pages">
                            <i class="fa-regular fa-file-lines pe-2"></i>
                            Pages
                        </a>
                        <ul id="pages" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link">Analytics</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link">Ecommerce</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link">Crypto</a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#dashboard"
                            aria-expanded="false" aria-controls="dashboard">
                            <i class="fa-solid fa-sliders pe-2"></i>
                            Dashboard
                        </a>
                        <ul id="dashboard" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link">Dashboard Analytics</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link">Dashboard Ecommerce</a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#auth"
                            aria-expanded="false" aria-controls="auth">
                            <i class="fa-regular fa-user pe-2"></i>
                            Auth
                        </a>
                        <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link">Login</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link">Register</a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-header">
                        Multi Level Nav
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#multi"
                            aria-expanded="false" aria-controls="multi">
                            <i class="fa-solid fa-share-nodes pe-2"></i>
                            Multi Level
                        </a>
                        <ul id="multi" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse"
                                    data-bs-target="#multi-two" aria-expanded="false" aria-controls="multi-two">
                                    Two Links
                                </a>
                                <ul id="multi-two" class="sidebar-dropdown list-unstyled collapse">
                                    <li class="sidebar-item">
                                        <a href="#" class="sidebar-link">Link 1</a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="#" class="sidebar-link">Link 2</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul> --}}
            </div>
        </aside>

        <!-- Main Component -->
        <div class="main" id="main">

            <!-- Navigation Bar -->
            <nav class="navbar navbar-expand px-3">

                <div>
                    <!-- Button for sidebar toggle -->
                    <button class="btn border-0 main-btn">
                        {{-- <span class="navbar-toggler-icon"></span> --}}
                        <i class="fa-solid fa-bars fs-5"></i>
                    </button>
                </div>

            </nav>

            <!-- Main Content -->
            <main class="content">
                <div>
                    <div>
                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        const departmentMapping = @json($departments);
        const roleMapping = @json($roles);
        const frequencyMapping = @json(config('static_data.frequency'));
        const startWorkingOnMapping = @json(config('static_data.start_working_on'));
        const submitOnMapping = @json(config('static_data.submit_on'));
    </script>

</body>
</html>

<style>
    svg {
        margin-right: 15px !important;;
    }

    svg path:nth-of-type(1) {
        fill: #BAC0C6; /* First path color */
    }

    svg path:not(:nth-of-type(1)) {
        fill: #5B6B79; /* Style for paths other than the first one */
    }

    body.dark svg path:nth-of-type(1) {
        fill: #42474C; /* First path color */
    }

    body.dark svg path:not(:nth-of-type(1)) {
        fill: #A0A3A6; /* Style for paths other than the first one */
    }

    svg.svg-active path:nth-of-type(1) {
        fill: #A7C2FD; /* First path color */
    }

    svg.svg-active path:not(:nth-of-type(1)) {
        fill: #4480FF; /* Style for paths other than the first one */
    }

    body.dark svg.svg-active path:nth-of-type(1) {
        fill: #2B4A87; /* First path color */
    }

    body.dark svg.svg-active path:not(:nth-of-type(1)) {
        fill: #4480FF; /* Style for paths other than the first one */
    }

    .siderbar-text {
        margin-bottom: 10px !important;
    }

    .sidebar-text.active {
        color: #4982FF !important;
    }

    body.dark .sidebar-text.active {
        color: #4076EA !important;
    }
</style>