<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Community Health Center') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f766e 0%, #134e4a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .auth-card {
            max-width: 440px;
            width: 100%;
            border: none;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
        }
        .auth-brand-icon {
            width: 50px;
            height: 50px;
            background-color: #ccfbf1;
            color: #0f766e;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
        }
        .btn-chc {
            background-color: #0f766e;
            border-color: #0f766e;
            color: #ffffff !important;
            font-weight: 600;
            padding: 0.65rem 1.25rem;
            box-shadow: 0 4px 10px rgba(15, 118, 110, 0.25);
            transition: all 0.2s ease-in-out;
        }
        .btn-chc:hover, .btn-chc:focus, .btn-chc:active {
            background-color: #115e59 !important;
            border-color: #115e59 !important;
            color: #ffffff !important;
            box-shadow: 0 6px 14px rgba(15, 118, 110, 0.35);
            transform: translateY(-1px);
        }
        .form-control:focus {
            border-color: #0f766e;
            box-shadow: 0 0 0 0.25rem rgba(15, 118, 110, 0.2);
        }
    </style>
</head>
<body>
    <div class="card auth-card bg-white p-4 p-md-5">
        <div class="text-center mb-4">
            <div class="auth-brand-icon mb-2">
                <i class="bi bi-hospital"></i>
            </div>
            <h3 class="fw-bold text-dark mb-1">Community Health</h3>
            <p class="text-muted small mb-0">Center Management System</p>
        </div>

        {{ $slot }}
    </div>

    <!-- Bootstrap 5 JS Bundle via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
