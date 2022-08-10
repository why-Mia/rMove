<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-dark darker disabled:opacity-25']) }}>
    {{ $slot }}
</button>
