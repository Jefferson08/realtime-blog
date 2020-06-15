<?php

namespace App\Http\Controllers;

use App\Category;
use App\Like;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{

    public function __construct(){
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('can:update-post,post')->only(['edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = array();

        if($request->query('category')){
            $category_id = $request->query('category');

            if(Category::where('id', $category_id)->exists()){
                $data['posts'] = Post::where('category_id', $category_id)->orderBy('created_at', 'desc')->paginate(2);
                $data['current_category'] = Category::find($category_id);
            } else {
                return redirect('home');
            }
        } else {
            $data['posts'] = Post::orderBy('created_at', 'desc')->paginate(2);
        }

        $data['categories'] = Category::all();

        return view('home')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();

        return view('posts.create')->with('categories', $categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'title',
            'description',
            'category',
            'body'
        ]);

        $validator = Validator::make($data, [
            'title' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:255'],
            'category' => 'exists:App\Category,id',
            'body' => ['required', 'string']
        ]);

        if($validator->fails()){
            return redirect()->route('posts.create')
            ->withErrors($validator)
            ->withInput();
        }

        $post = new Post();

        $post->title = $data['title'];
        $post->description = $data['description'];
        $post->body = $data['body'];
        $post->category_id = intval($data['category']);
        $post->user_id = Auth::id();
        
        $post->save();

        return redirect()->route('myposts')->with('success', 'Post created!!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $data = array();

        $categories = Category::all();

        $data['post'] = $post;
        $data['categories'] = $categories;

        return view('posts.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $data = $request->only([
            'title',
            'description',
            'category',
            'body'
        ]);

        $validator = Validator::make($data, [
            'title' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:255'],
            'category' => 'exists:App\Category,id',
            'body' => ['required', 'string']
        ]);

        if($validator->fails()){
            return redirect()->route('posts.create')
            ->withErrors($validator)
            ->withInput();
        }
        
        $post->title = $data['title'];
        $post->description = $data['description'];
        $post->body = $data['body'];
        $post->category_id = intval($data['category']);
        
        $post->save();

        return redirect()->route('myposts')->with('success', 'Post updated!!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('myposts')->with('success', 'Post deleted!!!');
    }

    public function like(Post $post){

        $user_id = Auth::id();
        $response = array();
        $response['success'] = false;
        $response['liked'] = false;

        if ($post->liked()) {

            $post->likes->where('user_id', $user_id)->first()->delete();
            
            $response['success'] = true;
            $response['message'] = "Post Unliked";

            echo json_encode($response);
        } else {
            $like = new Like();
            $like->post_id = $post->id;
            $like->user_id = $user_id;
            $like->save();

            $response['success'] = true;
            $response['liked'] = true;
            $response['message'] = "Post Liked";

            echo json_encode($response);
        }
    }
}
