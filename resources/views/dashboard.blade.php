<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-main-650 text-gray-800 dark:text-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-8 border-b border-gray-200  dark:border-gray-600">
                    <?php if(auth()->user()->robloxAccounts()->where('is_primary_account',true)->exists()){ ?>
                    <?php $primary_roblox_account = auth()->user()->robloxAccounts()->where('is_primary_account',true)->first(); ?>
                    <div class="grid grid-cols-12">
                        <div class="col-span-12 md:col-span-6 lg:col-span-3 md:mr-5">
                            <div class="bg-gray-100 dark:bg-main-750 rounded-lg">
                                <img class="object-contain w-full bg-gray-200 dark:bg-main-850 rounded-t-lg" style="max-height:250px;" src="<?php echo $primary_roblox_account->avatar_image_url; ?>">
                                <div class="p-4 pt-3">
                                    <h1 class="text-2xl text-center text-gray-800 overflow-hidden dark:text-gray-200">{{ auth()->user()->name }}</h1>
                                    <h2 class="text-sm text-center text-gray-600 overflow-hidden dark:text-gray-400">@<?php echo $primary_roblox_account->username; ?></h2>
                                    <div class="w-10/12 border-b border-gray-200  dark:border-gray-700 my-3 mx-auto"></div>
                                    <p class="text-center text-gray-600 dark:text-gray-400">Stats or something go here?</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 md:col-span-6 lg:col-span-9  md:ml-5">
                            <div class=" rounded-lg h-full">
                            </div>
                        </div>
                        <div class="col-span-12 mt-10">
                            <!-- Tab Contents -->
                            <!-- Tabs -->
                            <ul id="tabs" class="flex w-full pt-2d bg-gray-300 dark:bg-main-900 rounded-t-lg">
                                <li class="dark:text-white w-full h-full text-center mb-0 font-semibold text-gray-800 opacity-50 hover:opacity-100"><a class="w-full px-4 py-2 block" id="default-tab" href="#first-tab">Surf</a></li>
                                <li class="dark:text-white w-full h-full text-center mb-0 font-semibold text-gray-800 opacity-50 hover:opacity-100"><a class="w-full px-4 py-2 block" href="#second-tab">Bhop</a></li>
                                <li class="dark:text-white w-full h-full text-center mb-0 font-semibold text-gray-800 opacity-50 hover:opacity-100"><a class="w-full px-4 py-2 block" href="#third-tab">All</a></li>
                            </ul>
                            <div class="hidden border-b-4 -mb-px border-blue-400"></div>
                            
                            <div id="tab-contents">
                                <table id="first-tab" class="times-table block w-full text-left border-x-2 border-b-2 dark:bg-main-700 border-gray-200 dark:border-main-800">
                                    <tr class="table-header bg-gray-200 dark:bg-main-800">
                                    <th class="map-name">Map</th>
                                    <th class="time">Time</th>
                                    <th class="style">Style</th>
                                    <th class="date">Date</th>
                                    </tr>
                                    <?php $sample_json = file_get_contents("sample-data/mia_times.json");
                                    $sample_times = json_decode($sample_json);
                                    for ($i=0; $i < count($sample_times); $i++) { 
                                        if($sample_times[$i]->game === 'surf') {
                                        ?>
                                        <tr class="border-b border-gray-200 dark:border-main-750  text-gray-700 dark:text-gray-100 hover:text-black dark:hover:text-white">
                                            <td class="map-name"><span><?php echo trim($sample_times[$i]->map); ?></span></td>
                                            <td class="time"><span><?php echo trim($sample_times[$i]->time); ?></span></td>
                                            <td class="style"><span><?php echo trim($sample_times[$i]->style); ?></span></td>
                                            <td class="date"><span><?php echo trim($sample_times[$i]->date); ?></span></td>
                                        </tr>
                                        <?php
                                        }
                                    } ?>
                                </table> 
                                <table id="second-tab" class="times-table block w-full text-left border-x-2 border-b-2 dark:bg-main-700 border-gray-200 dark:border-main-700 rounded-lg">
                                    <tr class="table-header bg-gray-200 dark:bg-main-800">
                                        <th class="map-name">Map</th>
                                        <th class="time">Time</th>
                                        <th class="style">Style</th>
                                        <th class="date">Date</th>
                                    </tr>
                                    <?php $sample_json = file_get_contents("sample-data/mia_times.json");
                                    $sample_times = json_decode($sample_json);
                                    for ($i=0; $i < count($sample_times); $i++) { 
                                        if($sample_times[$i]->game === 'bhop') {
                                        ?>
                                        <tr class="border-b border-gray-200 dark:border-main-750  text-gray-700 dark:text-gray-100 hover:text-black dark:hover:text-white">
                                            <td class="map-name"><span><?php echo trim($sample_times[$i]->map); ?></span></td>
                                            <td class="time"><span><?php echo trim($sample_times[$i]->time); ?></span></td>
                                            <td class="style"><span><?php echo trim($sample_times[$i]->style); ?></span></td>
                                            <td class="date"><span><?php echo trim($sample_times[$i]->date); ?></span></td>
                                        </tr>
                                        <?php
                                        }
                                    } ?>
                                </table> 
                                <table id="third-tab" class="times-table block w-full text-left border-x-2 border-b-2 dark:bg-main-700 border-gray-200 dark:border-main-700 rounded-lg">
                                    <tr class="table-header bg-gray-200 dark:bg-main-800">
                                        <th class="map-name">Map</th>
                                        <th class="time">Time</th>
                                        <th class="style">Style</th>
                                        <th class="date">Date</th>
                                    </tr>
                                    <?php $sample_json = file_get_contents("sample-data/mia_times.json");
                                    $sample_times = json_decode($sample_json);
                                    for ($i=0; $i < count($sample_times); $i++) { 
                                        ?>
                                        <tr class="border-b border-gray-200 dark:border-main-750  text-gray-700 dark:text-gray-100 hover:text-black dark:hover:text-white">
                                            <td class="map-name"><span><?php echo trim($sample_times[$i]->map); ?></span></td>
                                            <td class="time"><span><?php echo trim($sample_times[$i]->time); ?></span></td>
                                            <td class="style"><span><?php echo trim($sample_times[$i]->style); ?></span></td>
                                            <td class="date"><span><?php echo trim($sample_times[$i]->date); ?></span></td>
                                        </tr>
                                        <?php
                                    } ?>
                                </table> 
                            </div>
                              
                        </div>
                    </div>
                    <?php }else{ ?>


                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
