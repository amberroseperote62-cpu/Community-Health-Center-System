<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-info alert-dismissible fade show small" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Demo Credentials Notice -->
    <div class="alert alert-light border small text-muted mb-4">
        <div class="fw-bold text-dark"><i class="bi bi-info-circle me-1 text-primary"></i> Demo Credentials:</div>
        <div><strong>Admin:</strong> admin@example.com / password</div>
        <div><strong>Staff:</strong> staff@example.com / password</div>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold small">Email Address</label>
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="name@example.com">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Password -->
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <label for="password" class="form-label fw-semibold small mb-0">Password</label>
                @if (Route::has('password.request'))
                    <a class="small text-decoration-none fw-semibold" href="{{ route('password.request') }}" style="color: #0f766e;">
                        Forgot password?
                    </a>
                @endif
            </div>
            <div class="input-group mt-1">
                <span class="input-group-text bg-light text-secondary"><i class="bi bi-lock"></i></span>
                <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Remember Me -->
        <div class="mb-3 form-check">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label for="remember_me" class="form-check-label small text-secondary">Remember me</label>
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-chc btn-lg fw-semibold fs-6 shadow-sm">
                <i class="bi bi-box-arrow-in-right me-2"></i> Sign In
            </button>
        </div>

        @if (Route::has('register'))
            <div class="text-center small text-muted">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-decoration-none fw-bold ms-1" style="color: #0f766e;">
                    Register here <i class="bi bi-arrow-right-short"></i>
                </a>
            </div>
        @endif
    </form>
</x-guest-layout>
