<?php

use App\Classes\TableOfTimes;

function formatGame($game){
    $games_as_array = ["","Bhop","Surf"];
    if(array_key_exists($game,$games_as_array)){
        return $games_as_array[$game];
    }
    return '???';
}

function timeDifference($datetime, $full = false) {
    $UTC = new DateTimeZone('UTC');
    $now = new DateTime('now',$UTC);
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;
    if($ago->getTimestamp() <= 1514764800){
        $too_long_ago = new DateTime();
        $too_long_ago->setTimestamp(1514764800);
        $diff_y = $now->diff($too_long_ago);
        return 'Over '.$diff_y->y.' years ago';
    }
    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
?>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Map') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-main-650 text-gray-800 dark:text-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-8 border-b border-gray-200  dark:border-gray-600">
                    <div class="grid grid-cols-12">
                        <div class="col-span-12 md:col-span-6 lg:col-span-3 md:mr-5">
                            <div class="bg-gray-100 dark:bg-main-750 rounded-lg">
                                <img class="object-contain w-full bg-gray-200 dark:bg-main-850 rounded-t-lg" style="max-height:250px;" src="<?php echo $map->map_image_url; ?>">
                                <div class="p-4 pt-3">
                                    <h1 class="text-2xl text-center text-gray-800 overflow-hidden dark:text-gray-200">{{ $map->displayname }}</h1>
                                    <h2 class="text-sm text-center text-gray-600 overflow-hidden dark:text-gray-400">by <?php echo $map->creator; ?></h2>
                                    <div class="w-10/12 border-b border-gray-200  dark:border-gray-700 my-3 mx-auto"></div>
                                    <p class="text-center text-gray-700 dark:text-gray-300 mt-2">Released</p>
                                    <p class="text-center text-gray-600 dark:text-gray-400"><?php echo timeDifference($map->date) ?></p>
                                    <p class="text-center text-gray-700 dark:text-gray-300 mt-2">Total times</p>
                                    <p class="text-center text-gray-600 dark:text-gray-400"><?php echo count($map->times) ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 md:col-span-6 lg:col-span-9  md:ml-5">
                            <div class=" rounded-lg h-full">
                            </div>
                        </div>
                        <?php //;?>
                        <div class="col-span-12 mt-10">
                            <div id="times-filters" class="flex justify-end mb-2 gap-2 flex-wrap">
                                <select class="bg-gray-100 dark:bg-main-900 border-0 rounded-lg" required name="styles" id="style-filter" value="1">
                                    <option value="1" selected="selected">Autohop</option>
                                    <?php if($map->game === 1){ //only show scroll on bhop maps ?>
                                            <option value="2">Scroll</option>
                                    <?php }?>
                                    <option value="3">Sideways</option>
                                    <option value="4">Half-Sideways</option>
                                    <option value="5">W-Only</option>
                                    <option value="6">A-Only</option>
                                    <option value="all">All</option>
                                    <option hidden value="7">Faste</option>
                                    <option hidden value="8">Sustain</option>
                                </select>
                                <input id="search-filter" class="bg-gray-100 dark:bg-main-900 border-0 rounded-lg" type="text" placeholder="Search">
                                <button class="bg-gray-100 dark:bg-main-900 border-0 rounded-lg w-10 h-10 toggle-visibility" data-click-target="#times-advanced-filters">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="m-2 icon icon-tabler icon-tabler-adjustments-horizontal" viewBox="0 0 24 24" stroke-width="1.5" stroke="#fff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="14" cy="6" r="2" />
                                        <line x1="4" y1="6" x2="12" y2="6" />
                                        <line x1="16" y1="6" x2="20" y2="6" />
                                        <circle cx="8" cy="12" r="2" />
                                        <line x1="4" y1="12" x2="6" y2="12" />
                                        <line x1="10" y1="12" x2="20" y2="12" />
                                        <circle cx="17" cy="18" r="2" />
                                        <line x1="4" y1="18" x2="15" y2="18" />
                                        <line x1="19" y1="18" x2="20" y2="18" />
                                    </svg>
                                </button>
                            </div>
                            <div id="times-advanced-filters" class="mb-4 bg-gray-200 dark:bg-main-850 p-4 rounded-lg grid grid-cols-12 gap-3 hidden">
                                <div class="col-span-3">
                                    <label class="ml-1 mb-1 block" for="sort-by">Sort By:</label>
                                    <select class="bg-gray-0 dark:bg-main-750 border-0 rounded-lg w-full" required name="sort-by" id="sort-by-filter">
                                        <option value="time" selected="selected">Time</option>
                                        <option value="date-newest">Date (Newest first)</option>
                                        <option value="date-oldest">Date (Oldest first)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="hidden border-b-4 -mb-px border-blue-400"></div>
                            
                            <div id="tab-contents">
                                <?php
                                $times = $map->times()->where('style', '=', 1)->orderby('time', 'asc')->get();
                                $times_table = new TableOfTimes($times, 'map');
                                ?>
                                <div id="times-tab-1" class="times-table block w-full text-left rounded-lg dark:bg-main-700 border-gray-200 dark:border-main-800">
                                    <div class="tr table-header bg-gray-200 dark:bg-main-900 rounded-t-lg">
                                        <div class="th name">User</div>
                                        <div class="th time">Time</div>
                                        <div class="th style">Style</div>
                                        <div class="th date">Date</div>
                                    </div>
                                    <div class="table-content">
                                        <?php echo $times_table->generateTable(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
