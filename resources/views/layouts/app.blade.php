<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Community Health Center - Patient Management System">

    <title>{{ config('app.name', 'Community Health Center') }} - @yield('title', 'Dashboard')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap Icons via CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom Styling for Healthcare Application UI -->
    <style>
        :root {
            --chc-primary: #0f766e;
            --chc-primary-dark: #115e59;
            --chc-primary-light: #14b8a6;
            --chc-sidebar-bg: #134e4a;
            --chc-sidebar-hover: #115e59;
            --chc-body-bg: #f8fafc;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--chc-body-bg);
            color: #1e293b;
        }

        .chc-sidebar {
            width: 260px;
            min-height: 100vh;
            background: linear-gradient(180deg, #134e4a 0%, #042f2e 100%);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .chc-brand-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #5eead4;
        }

        .chc-nav-link {
            color: #ccfbf1 !important;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s ease-in-out;
            margin-bottom: 0.25rem;
            text-decoration: none;
        }

        .chc-nav-link:hover {
            background-color: var(--chc-sidebar-hover);
            color: #ffffff !important;
            transform: translateX(3px);
        }

        .chc-nav-link.active {
            background-color: var(--chc-primary-light) !important;
            color: #042f2e !important;
            font-weight: 600;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .card {
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
            transition: box-shadow 0.2s ease;
        }

        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 1.25rem;
            font-weight: 600;
        }

        .btn-chc-primary {
            background-color: var(--chc-primary);
            border-color: var(--chc-primary);
            color: #ffffff !important;
            font-weight: 600;
            box-shadow: 0 2px 6px rgba(15, 118, 110, 0.25);
            transition: all 0.2s ease-in-out;
        }

        .btn-chc-primary:hover, .btn-chc-primary:focus, .btn-chc-primary:active {
            background-color: var(--chc-primary-dark) !important;
            border-color: var(--chc-primary-dark) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(15, 118, 110, 0.35);
            transform: translateY(-1px);
        }

        .btn-chc-danger {
            background-color: #dc2626;
            border-color: #dc2626;
            color: #ffffff !important;
            font-weight: 600;
            box-shadow: 0 2px 6px rgba(220, 38, 38, 0.25);
            transition: all 0.2s ease-in-out;
        }

        .btn-chc-danger:hover, .btn-chc-danger:focus, .btn-chc-danger:active {
            background-color: #b91c1c !important;
            border-color: #b91c1c !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.35);
            transform: translateY(-1px);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--chc-primary);
            box-shadow: 0 0 0 0.25rem rgba(15, 118, 110, 0.2);
        }

        .table th {
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            color: #64748b;
            background-color: #f8fafc;
        }

        .badge {
            font-weight: 500;
            padding: 0.4em 0.65em;
        }

        .stats-icon-box {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
    </style>
</head>
<body>

<div class="d-flex">
    <!-- Sidebar Navigation -->
    <aside class="chc-sidebar text-white d-flex flex-column flex-shrink-0 p-3">
        <!-- Brand Header -->
        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-white text-decoration-none pb-3 mb-3 border-bottom border-light border-opacity-25">
            <div class="chc-brand-icon me-2">
                <i class="bi bi-hospital fs-4"></i>
            </div>
            <div>
                <div class="fw-bold fs-6 lh-1">Community Health</div>
                <small class="text-teal-200 text-opacity-75" style="color: #99f6e4; font-size: 0.75rem;">Center System</small>
            </div>
        </a>

        <!-- Main Navigation Links -->
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="chc-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 fs-5"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('patients.index') }}" class="chc-nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}">
                    <i class="bi bi-people fs-5"></i>
                    <span>Patients</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('health-workers.index') }}" class="chc-nav-link {{ request()->routeIs('health-workers.*') ? 'active' : '' }}">
                    <i class="bi bi-person-badge fs-5"></i>
                    <span>Health Workers</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('appointments.index') }}" class="chc-nav-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check fs-5"></i>
                    <span>Appointments</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('consultations.index') }}" class="chc-nav-link {{ request()->routeIs('consultations.*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard2-pulse fs-5"></i>
                    <span>Consultations</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('medicines.index') }}" class="chc-nav-link {{ request()->routeIs('medicines.*') ? 'active' : '' }}">
                    <i class="bi bi-capsule fs-5"></i>
                    <span>Medicines</span>
                </a>
            </li>
        </ul>

        <!-- User Information & Logout -->
        <div class="pt-3 border-top border-light border-opacity-25">
            <div class="d-flex align-items-center mb-2">
                <div class="rounded-circle bg-light text-dark fw-bold d-flex align-items-center justify-center me-2 text-center" style="width: 36px; height: 36px; line-height: 36px;">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="text-truncate" style="max-width: 150px;">
                    <div class="small fw-semibold text-white text-truncate">{{ auth()->user()->name ?? 'User' }}</div>
                    <span class="badge bg-light text-dark text-capitalize" style="font-size: 0.7rem;">
                        {{ auth()->user()->role ?? 'staff' }}
                    </span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Sign Out</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Container -->
    <div class="flex-grow-1 d-flex flex-column min-vh-100">
        <!-- Top Navigation Header -->
        <header class="bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h4 mb-0 fw-bold text-dark">@yield('title', 'Dashboard')</h1>
                <small class="text-muted">@yield('subtitle', 'Community Health Center System')</small>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="text-muted small d-none d-md-flex align-items-center gap-1">
                    <i class="bi bi-calendar3 text-primary"></i>
                    <span>{{ now()->format('l, F d, Y') }}</span>
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name ?? 'Account' }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-gear me-2"></i>Profile Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Sign Out
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Main Workspace -->
        <main class="flex-grow-1 p-4">
            <!-- Flash Success Alert -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill fs-5 me-2 text-success"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Flash Error Alert -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-5 me-2 text-danger"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Validation Errors Alert -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <div class="fw-bold mb-1"><i class="bi bi-x-circle-fill me-1"></i> Please correct the errors below:</div>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{ $slot ?? '' }}
            @yield('content')
        </main>

        <!-- Application Footer -->
        <footer class="bg-white border-top py-3 px-4 text-center text-muted small">
            Community Health Center System &copy; {{ date('Y') }} &middot; Powered by Laravel &amp; Bootstrap
        </footer>
    </div>
</div>

<!-- Reusable Bootstrap Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow border-0">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteConfirmModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Confirm Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-1 fw-semibold text-dark fs-6" id="deleteModalMessage">Are you sure you want to delete this record?</p>
                <small class="text-muted">This action cannot be undone. Associated data may also be affected.</small>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="globalDeleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash-fill me-1"></i> Confirm Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 Bundle JS (Popper included) via CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
    // Global listener for delete modal trigger buttons
    document.addEventListener('DOMContentLoaded', function () {
        const deleteModal = document.getElementById('deleteConfirmModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                if (!button) return;

                const action = button.getAttribute('data-delete-url');
                const message = button.getAttribute('data-delete-msg') || 'Are you sure you want to delete this record?';

                const form = document.getElementById('globalDeleteForm');
                const msgElem = document.getElementById('deleteModalMessage');

                if (form && action) {
                    form.setAttribute('action', action);
                }
                if (msgElem) {
                    msgElem.textContent = message;
                }
            });
        }
    });
</script>

@stack('scripts')
</body>
</html>
