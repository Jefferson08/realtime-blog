@extends('layouts.site')

@section('content')

<a href="#" class="js-colorlib-nav-toggle colorlib-nav-toggle"><i></i></a>
		<aside id="colorlib-aside" role="complementary" class="js-fullheight">
			<nav id="colorlib-main-menu" role="navigation">
				<ul>
                    <li class="colorlib-active"><a href="index.html">Home</a></li>
					@guest
					<li><a href="{{route('login')}}">Login</a></li>
					<li><a href="{{route('register')}}">Register</a></li>
                    @endguest
					@auth
                    <li><a href="{{route('myposts')}}">My posts</a></li>
					<li>
                        <a href="#" onclick="event.preventDefault(); document.querySelector('#form-logout').submit()">Logout</a>
                        <form method="POST" id="form-logout" action="{{route('logout')}}" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                    @endauth
				</ul>
			</nav>

			<div class="colorlib-footer">
				<div class="mb-4">
					<h3>Subscribe for newsletter</h3>
					<form action="#" class="colorlib-subscribe-form">
            <div class="form-group d-flex">
            	<div class="icon"><span class="icon-paper-plane"></span></div>
              <input type="text" class="form-control" placeholder="Enter Email Address">
            </div>
          </form>
				</div>
				<p class="pfooter"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
	  Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="icon-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
	  <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
			</div>
		</aside> <!-- END COLORLIB-ASIDE -->
		<div id="colorlib-main">
			<section class="ftco-section ftco-no-pt ftco-no-pb">
	    	<div class="container">
                <!--MAIN ROW-->
	    		<div class="row d-flex">

                    <!--POSTS COL-->
	    			<div class="col-xl-8 py-5 px-md-5">

                        @if (isset($current_category))
                            <h1>{{$current_category->title}}</h1>
                            <hr>
                        @else
                            <h1>Recent posts</h1>
                            <hr>
                        @endif
                        
                        @if (count($posts) > 0)
                            @foreach ($posts as $post)
                            <div class="col-md-12">
                                <div class="blog-entry-2 ftco-animate">
                                    <div class="text pt-4">
                                        <h3 class="mb-2"><a href="#">{{$post->title}}</a></h3>
                                        <p class="mb-4">{{$post->description}}</p>
                                        <div class="author mb-4 d-flex align-items-center">
                                            <a href="#" class="img" style="background-image: url(images/image_1.jpg);"></a>
                                            <div class="ml-3 info">
                                                <span>Written by</span>
                                                <h3><a href="#">{{$post->author->name}}</a>, <span>{{$post->created_at->format('F j, Y')}}</span></h3>
                                            </div>
                                        </div>
                                        <div class="meta-wrap d-md-flex align-items-center">
                                            <div class="half order-md-last text-md-right">
                                                <p class="meta">
                                                    <span><i class="icon-heart"></i>3</span>
                                                    <span><i class="icon-eye"></i>100</span>
                                                    <span><i class="icon-comment"></i>{{$post->comments->count()}}</span>
                                                </p>
                                            </div>
                                            <div class="half">
                                                <p><a href="{{route('post', $post->id)}}" class="btn btn-primary p-3 px-xl-4 py-xl-3">Continue Reading</a></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        
                            <!--PAGINATION LINKS-->
                            {{$posts->withQueryString()->links('pagination')}}
                        @else
                            <h1>No posts availiable</h1>
                        @endif
                        
                    </div><!--END POSTS COL-->

                    <!--CATEGORIES COL-->
                    
	    			<div class="col-xl-4 sidebar ftco-animate bg-light pt-5">
                        <div class="sidebar-box pt-md-4">
                        <form action="#" class="search-form">
                            <div class="form-group">
                            <span class="icon icon-search"></span>
                            <input type="text" class="form-control" placeholder="Type a keyword and hit enter">
                            </div>
                        </form>
                        </div>
                        <div class="sidebar-box ftco-animate">
                            <h3 class="sidebar-heading">Categories</h3>
                        <ul class="categories">
                            
                            @foreach ($categories as $category)
                                <li><a href="{{route('home', ['category' => $category->id])}}">{{$category->title}}<span>({{$category->posts->count()}})</span></a></li>
                            @endforeach
                            
                        </ul>
                        </div>

                    </div><!-- END CATEGORIES COL -->
	    		</div><!--END MAIN ROW-->
	    	</div>
	    </section>
		</div><!-- END COLORLIB-MAIN -->

@endsection
