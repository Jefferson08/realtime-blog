<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {

        $data = array();

        if($request->query('category')){
            $category_id = $request->query('category');

            if(Category::where('id', $category_id)->exists()){
                $data['posts'] = Post::where('category_id', $category_id)->paginate(1);
                $data['current_category'] = Category::find($category_id);
            } else {
                return redirect('home');
            }
        } else {
            $data['posts'] = Post::paginate(1);
        }

        $data['categories'] = Category::all();

        return view('home')->with($data);
    }
}
