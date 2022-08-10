<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn-dark text-sm border']) }}>
    {{ $slot }}
</button>