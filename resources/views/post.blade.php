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

                    <div class="half order-md-left text-md-left" style="width: 100%;">
                        <p class="meta">
                            <span>
                                <i class="{{ ($post->liked()) ? "icon-heart" : "icon-heart-o"}}" id="like"> {{$post->likes->count()}}</i> 
                            </span>
                            <span><i class="icon-eye"> {{$post->views_count}}</i></span>
                        </p>
                    </div>

                    <div class="pt-2 mt-2" style="width: 100%;">
                        <div id="comments">
                            <comments-component v-bind:post_id="{{$post->id}}"></comments-component>
                        </div>
                        
                        <!-- END comment-list -->
                    
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

@section('scripts')
   <script>

        function toggleLike() {
            $('#like').toggleClass("icon-heart-o")
            $('#like').toggleClass("icon-heart")
        }

        $(function(){
            $('#like').click(function(){
                $.ajax({
                    url: "{{route('like', $post)}}",
                    type: "GET",
                    dataType: "json",
                    success: function(response){
                        if (response.success === true) {
                            if (response.liked === true) {
                                toggleLike();
                                var likes = parseInt($('#like').html());
                                likes++;
                                $('#like').html(" "+ likes);
                            } else {
                                toggleLike();
                                var likes = parseInt($('#like').html());
                                likes--;
                                $('#like').html(" "+ likes);
                            }
                        }
                    },
                    statusCode:{
                        401: function(){
                            alert("You must be logged in to like this post!!!");
                            window.location.href = "{{route('login')}}";
                        }
                    }
                });
            });
        });
   </script>
@endsection

@endsection