<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    use HasFactory;
    
    public function robloxAccount(){
        return $this->belongsTo(RobloxAccount::class);
    }
    public function map(){
        return $this->belongsTo(Map::class);
    }

}
