<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
