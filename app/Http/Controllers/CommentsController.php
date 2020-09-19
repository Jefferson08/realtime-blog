<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Events\CommentDeleted;
use App\Events\NewComment;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CommentsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except('index');
        $this->middleware('can:delete-comment,comment')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Post $post)
    {

        $data = array();
        $data['comments'] = array();
        $data['comments_count'] = $post->comments->count();
        $data['likes_count'] = $post->likes->count();
        $data['post_liked'] = $post->liked();

        $comments = Comment::with('author')->where('post_id', $post->id)->orderBy('created_at', 'DESC')->paginate(3);

        foreach ($comments as $item) {
            $comment = array();

            $comment['id'] = $item->id;
            $comment['author'] = $item->author->name;

            if($item->author->photo){
                $comment['profile_photo'] = $item->author->photo->getUrl('thumb');
            } else {
                $comment['profile_photo'] = asset('/images/default.png');
            }

            $comment['created_at'] = $item->created_at->format('F j, Y \a\t H:ia');
            $comment['body'] = $item->body;
            $comment['can_delete'] = Gate::allows('delete-comment', $item);

            array_push($data['comments'], $comment);
        }

        return $data;
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

        broadcast(new NewComment($comment))->toOthers();

        if($comment->author->photo){
            $response['profile_photo'] = $comment->author->photo->getUrl('thumb');
        } else {
            $response['profile_photo'] = asset('/images/default.png');
        }

        $response['id'] = $comment->id;
        $response['author'] = $comment->author->name;
        $response['created_at'] = $comment->created_at->format('F j, Y \a\t H:ia');
        $response['body'] = $comment->body;
        $response['can_delete'] = Gate::allows('delete-comment', $comment);

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(Comment $comment)
    {
        
        $response = array();

        broadcast(new CommentDeleted($comment))->toOthers();
        
        $comment->delete();

        $response['message'] = "Comment successfully deleted!!";

        return json_encode($response);

    }
}
