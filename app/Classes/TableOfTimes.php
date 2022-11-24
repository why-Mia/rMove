<?php

namespace App\Classes;


class TableOfTimes
{
    private $times;
    private $type;

    /**
    * Create table of times
    * @param array $times The list of times, as returned by user/map->times()->get();
    * @param string $type The type of times list to return, either 'map' or 'user'.
    * - 'map' is for use when listing the times on a map
    * - 'user' is for use when listing times obtained by a user
    */

    function __construct($times, $type){
        $this->times = $times;
        $this->type = strtolower($type);
    }

    /**
    * @return string  A HTML list of times
    */
    public function generateTable(){
        $times = $this->times;
        $times_html = '';
        foreach ($times as $time) {
            $times_html .= $this->generateTimeHtml($time);
        }
        return $times_html;
    }

    private function generateTimeHtml($time){
        if($this->type === 'user'){
            $time_html = '<a class="row game-'.trim($time->game).'" href="/map/'.$time->map->id.'">
            <div class="tr border-b border-gray-200 dark:border-main-750  text-gray-700 dark:text-gray-100 hover:text-black dark:hover:text-white">
            <div class="td name"><span>'.trim($time->map->displayname).'</span></div>
            <div class="td time"><span>'.trim($this->formatTime($time->time)).'</span></div>
            <div class="td style"><span>'.trim($this->formatStyle($time->style)).'</span></div>
            <div class="td date"><span>'.trim($time->date).'</span></div>
            </div>
            </a>';
        }
        else if($this->type === 'map'){
            if($time->robloxaccount->user !== null){
                $time_html = '<a class="row" href="/user/'.$time->robloxaccount->user->id.'">
                <div class="tr border-b border-gray-200 dark:border-main-750  text-gray-700 dark:text-gray-100 hover:text-black dark:hover:text-white">
                        <div class="td name"><span><span class="linked">'.trim($time->robloxaccount->username).'</span></span></div>
                        <div class="td time"><span>'.trim($this->formatTime($time->time)).'</span></div>
                        <div class="td style"><span>'.trim($this->formatStyle($time->style)).'</span></div>
                        <div class="td date"><span>'.trim($time->date).'</span></div>
                </div>
                </a>';
            }
            else{
                $time_html = '<div class="row">
            <div class="tr border-b border-gray-200 dark:border-main-750  text-gray-700 dark:text-gray-100 hover:text-black dark:hover:text-white">
                    <div class="td name"><span>'.trim($time->robloxaccount->username).'</span></div>
                    <div class="td time"><span>'.trim($this->formatTime($time->time)).'</span></div>
                    <div class="td style"><span>'.trim($this->formatStyle($time->style)).'</span></div>
                    <div class="td date"><span>'.trim($time->date).'</span></div>
            </div>
            </div>';
            }
            
        }
        else{
            $time_html = '<a href="#">';
        }
        
        
        return $time_html;
    }

    private function formatTime($time){
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
    private function formatStyle($style){
        $styles_as_array = ["","Autohop","Scroll","Sideways","Half-Sideways","W-Only","A-Only","Backwards","Faste","Sustain"];
        if(array_key_exists($style,$styles_as_array)){
            return $styles_as_array[$style];
        }
        return '???';
    }
    private function formatGame($game){
        $games_as_array = ["","Bhop","Surf","All"];
        if(array_key_exists($game,$games_as_array)){
            return $games_as_array[$game];
        }
        return '???';
    }
}
