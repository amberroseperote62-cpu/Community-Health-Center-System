@props(['value'])

<label {{ $attributes->merge(['class' => 'form-label fw-semibold small text-dark']) }}>
    {{ $value ?? $slot }}
</label>
