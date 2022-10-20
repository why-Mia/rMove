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

    public function refresh(){
        $times = $this->get_recent();
        //return $new_maps;
        $total_updated_times = 0;
        $total_new_times = 0;
        foreach ($times as $time) {
            if(Time::where('id',$time->ID)->where('time','!=',$time->Time)->exists()){ //if a time with ID already exists, only update it if the time has changed
                $time_in_db = Time::where('id',$time->ID)->where('time','!=',$time->Time)->first(); 
                $total_updated_times++;
            }
            //Otherwise, make a new account to link
            else{
                $total_new_times++;
                $time_in_db = new Time;
                $time_in_db -> id = $time->ID;
            }
            $time_in_db -> time = $time->Time;
            $time_in_db -> roblox_account_id = $time->User;
            $time_in_db -> map_id = $time->Map;
            $time_in_db -> game = $time->Game;
            $time_in_db -> style = $time->Style;
            $time_in_db -> mode = $time->Mode;
            $time_in_db -> date = gmdate("Y-m-d H:i:s", $time->Date );
            $time_in_db->save(); 
        }
        return $total_new_times.' new times added, '.$total_updated_times.' updated.';
    }
    private function get_recent()
    {
        //$url = 'https://api.strafes.net/v1/time/recent';
        $url = 'https://api.strafes.net/v1/time/user/14402266';
        $times_api_request = new ApiRequest();
        $times_data = $times_api_request->get($url);
        $headers = $times_data['headers'];
        $data = $times_data['data'];
        return $data;
    }
    private function get_by_user($id)
    {
        $url = 'https://api.strafes.net/v1/map';
        $maps_api_request = new ApiRequest();
        $maps_data = $maps_api_request->get($url);
        $headers = $maps_data['headers'];
        $data = $maps_data['data'];
        for ($i=2; $i <= intval($headers['pagination-count'][0]); $i++) { 
            $url = 'https://api.strafes.net/v1/map?page='.$i;
            $maps_api_request = new ApiRequest();
            $maps_data = $maps_api_request->get($url);
            $data = array_merge($data,$maps_data['data']);
        }
        return $data;
    }
}
