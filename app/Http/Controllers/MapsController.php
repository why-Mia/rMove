<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Classes\ApiRequest;
use App\Models\RobloxAccount;
use App\Models\Map;

class MapsController extends Controller
{
    public function show($id)
    {
        return view('maps', [
            'map' => Map::findOrFail($id)
        ]);
    }

    public function refresh(){
        $maps = $this->get_all();
        //return $new_maps;
        $total_updated_maps = 0;
        $total_new_maps = 0;
        foreach ($maps as $map) {
            if(Map::where('id',$map->ID)->exists()){ 
                $map_in_db = Map::where('id',$map->ID)->first();
                $total_updated_maps++;
            }
            //Otherwise, make a new account to link
            else{
                $total_new_maps++;
                $map_in_db = new Map;
                $map_in_db -> id = $map->ID;
            }
            $map_in_db -> displayname = $map->DisplayName;
            $map_in_db -> creator = $map->Creator;
            $map_in_db -> game = $map->Game;
            $map_in_db -> date = gmdate("Y-m-d H:i:s", $map->Date );
            $map_in_db -> playcount = $map->PlayCount;
            $map_in_db->save(); 
        }
        return $total_new_maps.' new maps added, '.$total_updated_maps.' updated.';
    }
    private function get_all()
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
