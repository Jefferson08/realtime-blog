<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{

    public function __construct(){
        $this->middleware('auth')->except('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Post $post){
        $comments = array();

        foreach ($post->comments as $item) {
           $comment = array();

           $comment['id'] = $item->id;
           $comment['author'] = $item->author->name;
           $comment['created_at'] = $item->created_at->format('F j, Y \a\t H:ia');
           $comment['body'] = $item->body;

           array_push($comments, $comment);

        }

        return $comments;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Post $post)
    {
        
        $response = array();

        $comment = new Comment();

        $comment->post_id = $post->id;
        $comment->user_id = Auth::id();
        $comment->body = $request->message;

        $comment->save();

        $response['id'] = $comment->id;
        $response['author'] = $comment->author->name;
        $response['created_at'] = $comment->created_at->format('F j, Y \a\t H:ia');
        $response['body'] = $comment->body;

        return $response;
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function destroy($id)
    {
        //
    }
}
