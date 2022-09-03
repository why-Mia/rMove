<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-main-650 dark:text-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 border-b border-gray-200  dark:border-gray-600">
                    <?php if(auth()->user()->robloxAccounts()->where('is_primary_account',true)->exists()){ ?>
                    <?php $primary_roblox_account = auth()->user()->robloxAccounts()->where('is_primary_account',true)->first() ?>
                    <div class="grid grid-cols-12">
                        <div class="col-span-3 mr-5">
                            <div class="bg-gray-100 dark:bg-main-750 rounded-lg">
                                <img class="object-contain w-full bg-gray-200 dark:bg-main-850 rounded-t-lg" style="max-height:250px;" src="<?php echo $primary_roblox_account->avatar_image_url?>">
                                <div class="p-4 pt-3">
                                    <h1 class="text-2xl text-center text-gray-800 overflow-hidden dark:text-gray-200">{{ auth()->user()->name }}</h1>
                                    <h2 class="text-sm text-center text-gray-600 overflow-hidden dark:text-gray-400">@<?php echo $primary_roblox_account->username ?></h2>
                                    <div class="w-10/12 border-b border-gray-200  dark:border-gray-700 my-3 mx-auto"></div>
                                    <p class="text-center text-gray-600 dark:text-gray-400">Stats or something go here?</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-9">
                            <p>Test</p>
                        </div>
                    </div>
                    <?php }else{ ?>


                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
