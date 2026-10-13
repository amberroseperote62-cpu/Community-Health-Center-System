<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        @if (session('status') === 'profile-updated')
            <div class="alert alert-success alert-dismissible fade show small d-flex align-items-center mb-3" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-6"></i>
                <div class="fw-semibold">Your profile information has been saved successfully!</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="mb-3">
            <label for="profile_name" class="form-label fw-semibold text-dark">
                Full Name <span class="text-danger">*</span>
            </label>
            <div class="input-group">
                <span class="input-group-text bg-light text-secondary"><i class="bi bi-person"></i></span>
                <input id="profile_name" name="name" type="text"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                    placeholder="Enter your name">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="profile_email" class="form-label fw-semibold text-dark">
                Email Address <span class="text-danger">*</span>
            </label>
            <div class="input-group">
                <span class="input-group-text bg-light text-secondary"><i class="bi bi-envelope"></i></span>
                <input id="profile_email" name="email" type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email', $user->email) }}" required autocomplete="username"
                    placeholder="name@example.com">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="alert alert-warning mt-3 small p-2">
                    <i class="bi bi-exclamation-circle me-1"></i> Your email address is unverified.
                    <button form="send-verification" type="submit" class="btn btn-link btn-sm p-0 ms-1 align-baseline fw-bold text-decoration-none">
                        Click here to re-send the verification email.
                    </button>
                </div>

                @if (session('status') === 'verification-link-sent')
                    <div class="alert alert-success mt-2 small p-2">
                        <i class="bi bi-check-circle me-1"></i> A new verification link has been sent to your email address.
                    </div>
                @endif
            @endif
        </div>

        <div class="d-flex align-items-center gap-3 pt-2">
            <button type="submit" class="btn btn-chc-primary fw-semibold px-4 py-2 shadow-sm">
                <i class="bi bi-check-lg me-1"></i> Save Changes
            </button>
        </div>
    </form>
</section>
