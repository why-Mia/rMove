<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Classes\ApiRequest;
use App\Models\RobloxAccount;
use App\Models\Time;
 
class TimesController extends Controller
{
    /**
     * Show the profile for a given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */

    public static function refresh($id){
        $times = self::get_by_user($id);
        //return $new_maps;
        $total_updated_times = 0;
        $total_new_times = 0;
        foreach ($times as $time) {
            if(Time::where('id',$time->ID)->where('time','!=',$time->Time)->exists()){ //if a time with ID already exists, only update it if the time has changed
                $time_in_db = Time::where('id',$time->ID)->where('time','!=',$time->Time)->first(); 
                $total_updated_times++;
                $time_in_db -> time = $time->Time;
                $time_in_db -> roblox_account_id = $time->User;
                $time_in_db -> map_id = $time->Map;
                $time_in_db -> game = $time->Game;
                $time_in_db -> style = $time->Style;
                $time_in_db -> mode = $time->Mode;
                $time_in_db -> date = gmdate("Y-m-d H:i:s", $time->Date );
                $time_in_db->save(); 
            }
            elseif (!Time::where('id',$time->ID)->exists()){
                $total_new_times++;
                $time_in_db = new Time;
                $time_in_db -> id = $time->ID;
                $time_in_db -> time = $time->Time;
                $time_in_db -> roblox_account_id = $time->User;
                $time_in_db -> map_id = $time->Map;
                $time_in_db -> game = $time->Game;
                $time_in_db -> style = $time->Style;
                $time_in_db -> mode = $time->Mode;
                $time_in_db -> date = gmdate("Y-m-d H:i:s", $time->Date );
                $time_in_db->save(); 
            }
            
        }
        return $total_new_times.' new times added, '.$total_updated_times.' updated.';
    }
    private function get_recent($roblox_id)
    {
        $url = 'https://api.strafes.net/v1/time/recent';
        $strafes_recent_request = new ApiRequest($url);
        $strafes_recent_request->useStrafesNetApiKey();
        $strafes_recent_request->sendGetRequest();
        $new_times_data = $strafes_recent_request->getResponseData();
        $new_times_headers = $strafes_recent_request->getResponseHeaders();
        return $strafes_recent_request;
    }
    private static function get_by_user($id)
    {
        $url = 'https://api.strafes.net/v1/time/user/'.$id;
        $strafes_request = new ApiRequest($url);
        $strafes_request->useStrafesNetApiKey();
        $strafes_request->sendGetRequest();
        $user_times_data = $strafes_request->getResponseData();
        $user_times_headers = $strafes_request->getResponseHeaders();
        $data = $user_times_data;
        //TODO: put this in the ApiRequest class or something, so i dont have to loop it here and can just get all the data at once?
        //similar loop in maps controller
        for ($i=2; $i <= intval($user_times_headers['pagination-count'][0]); $i++) { 
            $url = 'https://api.strafes.net/v1/time/user/'.$id.'?page='.$i;
            $strafes_request = new ApiRequest($url);
            $strafes_request->useStrafesNetApiKey();
            $strafes_request->sendGetRequest();
            $user_times_data = $strafes_request->getResponseData();
            $data = array_merge($data,$user_times_data);
        }
        return $data;
    }
}
