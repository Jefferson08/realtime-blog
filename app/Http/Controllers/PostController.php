<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Post $post){

        $categories = Category::all();

        return view('post')->with([
            'post' => $post,
            'categories' => $categories
        ]);
    }
}
