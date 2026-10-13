<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-chc-primary fw-semibold px-4 py-2 shadow-sm']) }}>
    {{ $slot }}
</button>
