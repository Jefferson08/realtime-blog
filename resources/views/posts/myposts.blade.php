@extends('layouts.app')

@section('content')

<div class="container">
    @if (session('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i>Ok</h5>
        {{session('success')}}
    </div>
    @endif

    <h1>Your Posts</h1>
    <hr>
    <a class="btn btn-info" href="{{route('posts.create')}}" style="margin-left:10px;"><i class="fa fa-edit"></i>  New Post</a>
    <hr>
    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                    <tr>
                        <td style="vertical-align: middle;">{{$post->id}}</td>
                        <td style="vertical-align: middle;">{{$post->title}}</td>
                        <td style="vertical-align: middle;">{{$post->category->title}}</td>
                        <td style="vertical-align: middle;">
                            <div class="row" style="margin: 0;">
                                <a class="btn btn-success" href="{{route('posts.show', $post->id)}}"><i class="fa fa-eye"></i>  See</a>
                                <a class="btn btn-info" href="{{route('posts.edit', $post->id)}}" style="margin-left:10px;"><i class="fa fa-edit"></i>  Edit</a>
                                
                                <form method="POST" action="{{route('posts.destroy', $post)}}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit" onclick="return confirm('Deseja realmente excluir o post {{$post->title}} ?')" style="margin-left: 10px;"><i class="fa fa-user-times"></i>  Excluir</button>
                                </form>
                              
                            </div>
                        </td>
                      </tr>
                    @endforeach
                
                </tbody>
                
            </table>
            {{$posts->links()}}
        </div>
    </div>
</div>

@endsection