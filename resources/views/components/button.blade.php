<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-dark disabled:opacity-25']) }}>
    {{ $slot }}
</button>
