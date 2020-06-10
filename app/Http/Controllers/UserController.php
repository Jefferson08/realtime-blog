<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function posts(){

        $posts = Post::where('user_id', Auth::id())->paginate(5);

        return view('posts.myposts')->with('posts', $posts);
    }
}
