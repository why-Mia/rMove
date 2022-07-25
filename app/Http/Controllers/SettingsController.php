<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function update(\App\Http\Requests\UpdateSettingsRequest $request){
        auth()->user()->update($request->only(keys:['name','email']));
        if($request->input(key:'password')){
            auth()->user()->update([
                'password' => bcrypt($request->input(key:'password'))
            ]);
        }
        return redirect()->route(route:'settings')->with('message', 'Settings successfully updated');
    }
    public function check_username(\App\Http\Requests\CheckUsernameRequest $request){
        $response = file_get_contents('http://api.roblox.com/users/get-by-username?username='.$request['roblox_username']);
        return $response;
    }
}
