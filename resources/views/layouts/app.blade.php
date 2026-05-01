<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ClinicMS')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>

<div class="layout">

    <aside class="sidebar">
        <div class="sidebar-brand">
            <h1> ClinicMS</h1>
            <span>Healthcare Management</span>
        </div>

        <div style="flex: 1; overflow-y: auto;">
            <p class="nav-section-label">Main</p>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="{{ route('appointments.calendar') }}" class="nav-link {{ request()->routeIs('appointments.calendar') ? 'active' : '' }}">
                <i class="bi bi-calendar3"></i> Calendar
            </a>

            <p class="nav-section-label">Records</p>
            <a href="{{ route('patients.index') }}" class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Patients
            </a>
            <a href="{{ route('doctors.index') }}" class="nav-link {{ request()->routeIs('doctors.*') ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i> Doctors
            </a>

            <p class="nav-section-label">Operations</p>
            <a href="{{ route('appointments.index') }}"
               class="nav-link {{ request()->routeIs('appointments.*') && !request()->routeIs('appointments.calendar') ? 'active' : '' }}">
                <i class="bi bi-clipboard2-pulse"></i> Appointments
            </a>
            <a href="{{ route('billing.index') }}" class="nav-link {{ request()->routeIs('billing.index') || request()->routeIs('billing.show') || request()->routeIs('billing.create') || request()->routeIs('billing.edit') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i> Billing
            </a>
            <a href="{{ route('billing.report') }}" class="nav-link {{ request()->routeIs('billing.report') ? 'active' : '' }}">
                <i class="bi bi-bar-chart"></i> Reports
            </a>
        </div>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="#" onclick="this.closest('form').submit()">
                    <i class="bi bi-box-arrow-left"></i> Logout
                </a>
            </form>
        </div>
    </aside>

    <div class="main">
        <div class="topbar">
            <div>
                <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
                <div class="topbar-sub">{{ now()->format('l, F j, Y') }}</div>
            </div>
            <div class="topbar-user">
                <i class="bi bi-person-circle"></i>
                {{ auth()->user()->name }}
                <a href="{{ route('profile.edit') }}" style="color: #94a3b8; margin-left: 4px;">
                    <i class="bi bi-gear"></i>
                </a>
            </div>
        </div>

        <div class="page">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('info'))
                <div class="alert alert-info">
                    <i class="bi bi-info-circle-fill"></i>
                    {{ session('info') }}
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>
                        <strong>Please fix the following:</strong>
                        <ul style="margin: 4px 0 0; padding-left: 16px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

</div>

@stack('scripts')
</body>
</html>