<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Post $post){

        $data = array();

        $data['post'] = $post;

        $categories = Category::all();
        $data['categories'] = $categories;

        if($post->tags){
            $tags = explode(' ', $post->tags->content);
            $data['tags'] = $tags;
        }

        $post->increment('views_count');

        return view('post')->with($data);
    }
}
