@extends('layouts.site')

@section('content')
<section class="ftco-section ftco-no-pt ftco-no-pb">
    <div class="container">
        <div class="row d-flex">
            <div class="col-lg-8 px-md-5 py-5">
                <div class="row pt-md-4">
                    <div class="container">
                        <h1 class="mb-3"style="width: 100%;">{{$post->title}}</h1>
                        <p>{{$post->body}}</p>
                    
                        <div class="author mb-2 d-flex align-items-center" style="width: 100%;">
                            <div class="info">
                                <span>Written by</span>
                                <h3><a href="#">{{$post->author->name}}</a>, <span>{{$post->created_at->format('F j, Y')}}</span></h3>
                            </div>
                        </div>

                        <hr style="width: 100%;">
                        
                        <div class="tag-widget post-tag-container mb-3 mt-3" style="width: 100%;">
                            <div class="tagcloud">
                            @isset($tags)
                                @foreach ($tags as $tag)
                                <a href="#" class="tag-cloud-link">{{$tag}}</a>
                                @endforeach
                            @endisset
                            </div>
                        </div>

                        <div id="app">
                            <comments-component v-bind:post_id="{{$post->id}}"></comments-component>
                        </div>
                    </div>
                </div><!-- END-->
            </div>

            <div class="col-lg-4 sidebar ftco-animate bg-light pt-5">
                <div class="sidebar-box pt-md-4">
                    <form action="#" class="search-form">
                        <div class="form-group">
                        <span class="icon icon-search"></span>
                        <input type="text" class="form-control" placeholder="Type a keyword and hit enter">
                        </div>
                    </form>
                </div>

                <div class="sidebar-box ftco-animate">
                    <h3 class="sidebar-heading">Links:</h3>
                    <ul class="categories">
                        <li><a href="{{ route('home')}}">Home</a></li>
                        @guest
                        <li><a href="{{ route('login')}}">Login</a></li>
                        <li><a href="{{ route('register')}}">Register</a></li>
                        @endguest
                        @auth
                        <li><a href="{{ route('myposts')}}">My Posts</a></li>
                        <li><a href="{{ route('users.edit', Auth::user())}}">Profile</a></li>
                        <li>
                            <a href="" onclick="event.preventDefault(); document.querySelector('#form-logout').submit()">Logout</a>
                            <form method="POST" id="form-logout" action="{{route('logout')}}" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                        @endauth
                    </ul>
                    <hr>
                    <h3 class="sidebar-heading">Categories:</h3>
                    <ul class="categories">
                        @foreach ($categories as $category)
                            <li><a href="{{route('home', ['category' => $category->id])}}">{{$category->title}} <span>({{$category->posts->count()}})</span></a></li>
                        @endforeach                        
                    </ul>
                </div>

            </div><!-- END COL -->
        </div>
    </div>
</section>

@endsection