<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    public function category(){
        return $this->belongsTo('\App\Category', 'category_id', 'id');
    }
 
    public function comments(){
        return $this->hasMany('\App\Comment', 'post_id', 'id');
    }
 
    public function author(){
        return $this->belongsTo('\App\User', 'user_id', 'id');
    }
 
    public function tags(){
        return $this->hasOne('\App\Tag', 'post_id', 'id');
    }
 
    public function likes(){
        return $this->hasMany('\App\Like', 'post_id', 'id');
    }

    public function liked(){

        if(Like::where(['post_id' => $this->id, 'user_id' => Auth::id()])->exists()){
            return true;
        } else{
            return false;
        }
    }
}
