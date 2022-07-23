@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-base']) }}>
    {{ $value ?? $slot }}
</label>
