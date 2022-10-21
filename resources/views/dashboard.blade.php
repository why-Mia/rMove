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

?>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <?php if(auth()->user()->robloxAccounts()->where('is_primary_account',true)->exists()){ ?>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-main-650 text-gray-800 dark:text-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-8 border-b border-gray-200  dark:border-gray-600">
                        <?php $primary_roblox_account = auth()->user()->robloxAccounts()->where('is_primary_account',true)->first(); ?>
                        <div class="grid grid-cols-12">
                            <div class="col-span-12 md:col-span-6 lg:col-span-3 md:mr-5">
                                <div class="bg-gray-100 dark:bg-main-750 rounded-lg">
                                    <img class="object-contain w-full bg-gray-200 dark:bg-main-850 rounded-t-lg" style="max-height:250px;" src="<?php echo $primary_roblox_account->avatar_image_url; ?>">
                                    <div class="p-4 pt-3">
                                        <h1 class="text-2xl text-center text-gray-800 overflow-hidden dark:text-gray-200">{{ $primary_roblox_account->displayname; }}</h1>
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
                                    <?php for ($i=1; $i <= 3; $i++) {
                                        $default = '';
                                        if(auth()->user()->primary_game === $i || ($i==3 && auth()->user()->primary_game==null)){
                                            $default = 'id="default-tab"';
                                        } ?>
                                        <li class="dark:text-white w-full h-full text-center mb-0 font-semibold text-gray-800 opacity-50 hover:opacity-100"><a class="w-full px-4 py-2 block" <?php echo $default ?> href="#times-tab-<?php echo $i ?>"><?php echo format_game($i) ?></a></li>
                                    <?php } ?>
                                </ul>
                                <div class="hidden border-b-4 -mb-px border-blue-400"></div>
                                
                                <div id="tab-contents">
                                    <?php
                                    //$times = $primary_roblox_account->times;
                                    //$roblox_accounts = $user->robloxAccounts()->get();
                                    $times = auth()->user()->times()->orderby('date', 'desc')->get();
                                    $all_times_html = '';
                                    $bhop_times_html = '';
                                    $surf_times_html = '';
                                    foreach ($times as $time) {
                                        $time_html = '<a href="/map/'.$time->map->id.'">
                                        <div class="tr border-b border-gray-200 dark:border-main-750  text-gray-700 dark:text-gray-100 hover:text-black dark:hover:text-white">
                                                <div class="td map-name"><span>'.trim($time->map->displayname).'</span></div>
                                                <div class="td time"><span>'.trim(format_time($time->time)).'</span></div>
                                                <div class="td style"><span>'.trim(format_style($time->style)).'</span></div>
                                                <div class="td date"><span>'.trim($time->date).'</span></div>
                                        </div>
                                        </a>';
                                        $all_times_html .= $time_html;
                                        switch ($time->game) {
                                            case 1:
                                                $bhop_times_html .= $time_html;
                                                break;
                                            case 2:
                                                $surf_times_html .= $time_html;
                                                break;
                                            default:
                                                error_log('a time with an invalid game exists somehow? id:'.$time->id);
                                                break;
                                        }
                                    }
    
                                    ?>
                                    <div id="times-tab-1" class="times-table block w-full text-left border-x-2 border-b-2 dark:bg-main-700 border-gray-200 dark:border-main-800">
                                        <div class="tr table-header bg-gray-200 dark:bg-main-800">
                                            <div class="th map-name">Map</div>
                                            <div class="th time">Time</div>
                                            <div class="th style">Style</div>
                                            <div class="th date">Date</div>
                                        </div>
                                    <?php echo $bhop_times_html; ?>
                                    </div>
                                    <div id="times-tab-2" class="times-table block w-full text-left border-x-2 border-b-2 dark:bg-main-700 border-gray-200 dark:border-main-800">
                                        <div class="tr table-header bg-gray-200 dark:bg-main-800">
                                            <div class="th map-name">Map</div>
                                            <div class="th time">Time</div>
                                            <div class="th style">Style</div>
                                            <div class="th date">Date</div>
                                        </div>
                                        <?php echo $surf_times_html; ?>
                                    </div>
                                    <div id="times-tab-3" class="times-table block w-full text-left border-x-2 border-b-2 dark:bg-main-700 border-gray-200 dark:border-main-800">
                                        <div class="tr table-header bg-gray-200 dark:bg-main-800">
                                            <div class="th map-name">Map</div>
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
        </div>
    <?php }else{ ?>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-main-650 text-gray-800 dark:text-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 sm:p-8 border-b border-gray-200  dark:border-gray-600 flex flex-col justify-center items-center">
                        <p>You need to link a Roblox account to view your profile.</p>
                        <x-button class="mt-4 px-7 py-3 text-base" data-modal-toggle="roblox-account-linking-modal">
                            Link Roblox Account
                        </x-button>
                    </div>
                </div>  
            </div>
        </div>
        <x-roblox-account-link-modals></x-roblox-account-link-modals>
    <?php } ?>
</x-app-layout>
