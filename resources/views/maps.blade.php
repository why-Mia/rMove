<?php
function format_time($time){
    $total_time_milliseconds = $time;
    $millisecs = floor($total_time_milliseconds % 1000);
    $secs = floor($total_time_milliseconds/1000 % 60);
    $mins = floor($total_time_milliseconds/1000 / 60 % 60);
    if($total_time_milliseconds > 3600000){
        $hours = floor($total_time_milliseconds/1000 / 3600);
        $timeFormat = sprintf('%02d:%02d:%02d.%03d', $hours, $mins, $secs, $millisecs);
    }
    else{
        $timeFormat = sprintf('%02d:%02d.%03d', $mins, $secs, $millisecs);
    }
    return $timeFormat;
}
function format_style($style){
    $styles_as_array = ["","Autohop","Scroll","Sideways","Half-Sideways","W-Only","A-Only","Backwards","Faste","Sustain"];
    if(array_key_exists($style,$styles_as_array)){
        return $styles_as_array[$style];
    }
    return '???';
}
function format_game($game){
    $games_as_array = ["","Bhop","Surf","All"];
    if(array_key_exists($game,$games_as_array)){
        return $games_as_array[$game];
    }
    return '???';
}

function time_elapsed_string($datetime, $full = false) {
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
                                    <p class="text-center text-gray-600 dark:text-gray-400"><?php echo time_elapsed_string($map->date) ?></p>
                                    <p class="text-center text-gray-700 dark:text-gray-300 mt-2">Total times</p>
                                    <p class="text-center text-gray-600 dark:text-gray-400"><?php echo count($map->times) ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 md:col-span-6 lg:col-span-9  md:ml-5">
                            <div class=" rounded-lg h-full">
                            </div>
                        </div>
                        <div class="col-span-12 mt-10">

                            <div class="hidden border-b-4 -mb-px border-blue-400"></div>
                            
                            <div id="tab-contents">
                                <?php
                                //$times = $primary_roblox_account->times;
                                //$roblox_accounts = $user->robloxAccounts()->get();
                                $times = $map->times()->orderby('time', 'asc')->get();
                                $all_times_html = '';
                                foreach ($times as $time) {
                                    $time_html = '<a href="/user/'.$time->robloxaccount->user->id.'">
                                    <div class="tr border-b border-gray-200 dark:border-main-750  text-gray-700 dark:text-gray-100 hover:text-black dark:hover:text-white">
                                            <div class="td map-name"><span>'.trim($time->robloxaccount->username).'</span></div>
                                            <div class="td time"><span>'.trim(format_time($time->time)).'</span></div>
                                            <div class="td style"><span>'.trim(format_style($time->style)).'</span></div>
                                            <div class="td date"><span>'.trim($time->date).'</span></div>
                                    </div>
                                    </a>';
                                    $all_times_html .= $time_html;
                                }

                                ?>
                                <div id="times-tab-1" class="times-table block w-full text-left border-x-2 border-b-2 dark:bg-main-700 border-gray-200 dark:border-main-800">
                                    <div class="tr table-header bg-gray-200 dark:bg-main-800">
                                        <div class="th map-name">User</div>
                                        <div class="th time">Time</div>
                                        <div class="th style">Style</div>
                                        <div class="th date">Date</div>
                                    </div>
                                <?php echo $all_times_html; ?>
                                </div>
                            </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
