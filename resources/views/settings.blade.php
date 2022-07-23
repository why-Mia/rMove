<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="font-bold text-lg mb-3 pb-1 w-auto inline-flex pr-8 border-b-2 border-gray-200">Site Settings</h2>
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />
                    <x-success-message />
                    <form method="POST" action="{{ route('settings.update') }}">
                        @method('PUT')
                        @csrf
                        <div class="grid grid-cols-2 gap-6">
                            <div class="grid grid-rows-2 gap-6">
                                <div>
                                    <x-label-lg for="name" :value="__('Name')" />
                                    <div class="flex justify-between mt-1">
                                        <div class="w-full text-to-update">
                                            <p class="name-text text-to-update py-2 px-3 border bg-gray-100 text-gray-500 rounded-l-md rounded-r-none select-none">{{ auth()->user()->name }}</p>
                                            <x-input id="name-edit" class="block w-full hidden rounded-r-none" type="text" name="name" value="{{ auth()->user()->name }}" autofocus />
                                        </div>
                                        <span class="btn-dark update-form-text toggle-visibility rounded-l-none border disabled:opacity-25 toggle-text " data-click-target="#name-edit,.name-text" data-toggle-text="Confirm">Edit</span>
                                    </div>
                                </div>
                                <div>
                                    <x-label-lg for="email" :value="__('Email')" />
                                    <div class="flex justify-between mt-1">
                                        <div class="w-full text-to-update">
                                            <p class="email-text py-2 px-3 border bg-gray-100 text-gray-500 rounded-l-md rounded-r-none select-none">{{ auth()->user()->email }}</p>
                                            <x-input id="email-edit" class="block w-full hidden rounded-r-none" type="email" name="email" value="{{ auth()->user()->email }}" autofocus />
                                        </div>
                                        <span class="btn-dark update-form-text toggle-visibility rounded-l-none border disabled:opacity-25 toggle-text " data-click-target="#email-edit,.email-text" data-toggle-text="Confirm">Edit</span>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-rows-2 gap-6">
                                <div>
                                    <x-label-lg for="new_password" :value="__('Password')" />
                                    <div class="flex justify-between mt-1">
                                        <div class="w-full">
                                            <p class="new-password-text py-2 px-3 border bg-gray-100 text-gray-500 rounded-l-md rounded-r-none select-none">********</p>
                                            <x-input id="new-password-edit" class="block w-full hidden rounded-r-none" type="password" name="password" autocomplete="new-password" />
                                        </div>
                                        <span class="btn-dark toggle-visibility rounded-l-none border disabled:opacity-25 toggle-text " data-click-target="#new-password-edit,.new-password-text,#confirm-password-box" data-toggle-text="Confirm">Edit</span>
                                    </div>
                                </div>
                                <div class="hidden" id="confirm-password-box">
                                    <x-label-lg for="confirm_password" :value="__('Confirm password')" />
                                    <div class="flex justify-between mt-1">
                                        <div class="w-full">
                                            <x-input id="confirm-password-edit" class="block w-full rounded-r-none" type="password" name="password" autocomplete="confirm-password" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <x-button class="ml-3">
                                {{ __('Update') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
