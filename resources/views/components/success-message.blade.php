@if (session('message'))
    <div {{ $attributes }}>
        <div class="font-medium text-green-600 mb-3">
            {{ __(session('message')) }}
        </div>
    </div>
@endif
