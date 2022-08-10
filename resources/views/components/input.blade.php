@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 bg-gray-100 focus:ring-opacity-50 dark:bg-main-450 dark:text-white dark:border-main-400']) !!}>
