@extends('layouts.site')

@section('content')
<section class="ftco-section ftco-no-pt ftco-no-pb">
    <div class="container">
        <div class="row d-flex">
            <div class="col-lg-8 px-md-5 py-5">
                <div class="row pt-md-4">
                    <h1 class="mb-3"style="width: 100%;">{{$post->title}}</h1>
                    <p>{{$post->body}}</p>
                   
                    <div class="author mb-2 d-flex align-items-center" style="width: 100%;">
                        <div class="info">
                            <span>Written by</span>
                            <h3><a href="#">{{$post->author->name}}</a>, <span>{{$post->created_at->format('F j, Y')}}</span></h3>
                        </div>
                    </div>
                    
                    <div class="tag-widget post-tag-container mb-3 mt-3">
                        <div class="tagcloud">
                            <a href="#" class="tag-cloud-link">Life</a>
                            <a href="#" class="tag-cloud-link">Sport</a>
                            <a href="#" class="tag-cloud-link">Tech</a>
                            <a href="#" class="tag-cloud-link">Travel</a>
                            <a href="#" class="tag-cloud-link">Travel</a>
                            <a href="#" class="tag-cloud-link">Travel</a>
                            <a href="#" class="tag-cloud-link">Travel</a>
                            <a href="#" class="tag-cloud-link">Travel</a>
                            <a href="#" class="tag-cloud-link">Travel</a>
                            <a href="#" class="tag-cloud-link">Travel</a>
                        </div>
                    </div>

                    <div class="pt-2 mt-2">
                        <h3 class="mb-4 font-weight-bold">{{$post->comments->count()}} Comments</h3>
                        <ul class="comment-list">
                            
                            @foreach ($post->comments as $comment)
                                <li class="comment">
                                    <div class="vcard bio">
                                        <img src="{{asset('images/image_1.jpg')}}" alt="Image placeholder">
                                    </div>
                                    <div class="comment-body">
                                        <h3>{{$comment->author->name}}</h3>
                                        <div class="meta">{{$comment->created_at->format('F j, Y \a\t H:ia')}}</div>
                                        <p>{{$comment->body}}</p>
                                    </div>
                                </li>
                            @endforeach
                           
                        </ul>
                        <!-- END comment-list -->
                    
                        <div class="comment-form-wrap pt-2">
                            <h3 class="mb-3">Leave a comment</h3>
                            <form action="#" class="p-3 p-md-5 bg-light">
                                <div class="form-group">
                                    <label for="message">Message</label>
                                    <textarea name="" id="message" cols="30" rows="5" class="form-control"></textarea>
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="Post Comment" class="btn py-3 px-4 btn-primary">
                                </div>
                            </form>
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
                    <h3 class="sidebar-heading">Categories</h3>
                    <ul class="categories">
                        @foreach ($categories as $category)
                            <li><a href="#">{{$category->title}} <span>({{$category->posts->count()}})</span></a></li>
                        @endforeach                        
                    </ul>
                </div>

            </div><!-- END COL -->
        </div>
    </div>
</section>
@endsection