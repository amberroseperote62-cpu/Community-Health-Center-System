<x-guest-layout>
    <div class="text-center mb-3">
        <h5 class="fw-bold text-dark mb-1">Reset Password</h5>
        <p class="text-muted small mb-0">Enter your email and we'll send a password reset link.</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-info alert-dismissible fade show small mb-3" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold small">Email Address</label>
            <div class="input-group">
                <span class="input-group-text bg-light text-secondary"><i class="bi bi-envelope"></i></span>
                <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-chc btn-lg fw-semibold fs-6 shadow-sm">
                <i class="bi bi-send-fill me-2"></i> Send Password Reset Link
            </button>
        </div>

        <div class="text-center small text-muted">
            <a href="{{ route('login') }}" class="text-decoration-none fw-bold" style="color: #0f766e;">
                <i class="bi bi-arrow-left me-1"></i> Back to Sign In
            </a>
        </div>
    </form>
</x-guest-layout>
