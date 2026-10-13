<x-guest-layout>
    <div class="text-center mb-4">
        <h5 class="fw-bold text-dark mb-1">Create an Account</h5>
        <p class="text-muted small mb-0">Join the Community Health Center System</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label fw-semibold small">Full Name</label>
            <div class="input-group">
                <span class="input-group-text bg-light text-secondary"><i class="bi bi-person"></i></span>
                <input id="name" class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Juan Dela Cruz">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold small">Email Address</label>
            <div class="input-group">
                <span class="input-group-text bg-light text-secondary"><i class="bi bi-envelope"></i></span>
                <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="name@example.com">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label fw-semibold small">Password</label>
            <div class="input-group">
                <span class="input-group-text bg-light text-secondary"><i class="bi bi-lock"></i></span>
                <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="new-password" placeholder="••••••••">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-text small text-muted">Minimum 8 characters.</div>
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-semibold small">Confirm Password</label>
            <div class="input-group">
                <span class="input-group-text bg-light text-secondary"><i class="bi bi-shield-lock"></i></span>
                <input id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Submit Button -->
        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-chc btn-lg fw-semibold fs-6 shadow-sm">
                <i class="bi bi-person-plus-fill me-2"></i> Register Account
            </button>
        </div>

        <!-- Login Link -->
        <div class="text-center small text-muted">
            Already registered?
            <a href="{{ route('login') }}" class="text-decoration-none fw-bold ms-1" style="color: #0f766e;">
                Sign In here <i class="bi bi-arrow-right-short"></i>
            </a>
        </div>
    </form>
</x-guest-layout>
