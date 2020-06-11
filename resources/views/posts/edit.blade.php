@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Post - {{$post->id}}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('posts.update', $post) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="title" class="col-md-4 col-form-label">Title</label>

                            <div class="col-md-12">
                                <input id="title" type="text" value="{{$post->title}}" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required autocomplete="title" autofocus>

                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description" class="col-md-4 col-form-label">Description</label>

                            <div class="col-md-12">
                                <input id="description" type="description" value="{{$post->description}}" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ old('description') }}" required autocomplete="description">

                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="category" class="col-md-4 col-form-label">Category</label>

                            <div class="col-md-12">

                                <select name="category" id="category" class="form-control @error('category') is-invalid @enderror">
                                    @foreach ($categories as $category)
                                        <option value="{{$category->id}}" {{ ($category->id == $post->category->id) ? "selected" : ""}}>{{$category->title}}</option>
                                    @endforeach 
                                </select>

                                @error('category')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="body" class="col-md-4 col-form-label">Body</label>

                            <div class="col-md-12">
                                <textarea name="body" id="body" cols="30" rows="10" class="form-control @error('body') is-invalid @enderror" required>{{$post->body}}</textarea>
                                @error('body')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
