@extends('layouts.app')
@section('title', 'Post Details')
@section('heading', 'Post Details')
@section('link_text', 'Goto All Posts')
@section('link', '/posts')

@section('content')
    @if(session('message'))
        <div class="alert alert-{{ session('status') }} alert-dismissible fade show" role="alert">
            <strong>{{ session('message') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="row my-4">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow">
                <img src="{{ asset('images/'.$post->image) }}" class="img-fluid card-img-top">
                <div class="card-body p-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="btn btn-dark rounded-pill">{{ $post->category }}</p>
                        <p class="lead">{{ \Carbon\Carbon::parse($post->created_at)->diffForHumans() }}</p>
                    </div>
                    <hr>
                    <h3 class="fw-bold text-primary">{{ $post->title }}</h3>
                    <div class="fw-bold text-primary">
                        Posted by {{ $post->user->name }}
                    </div>
                    <p>{{ $post->content }}</p>
                    <hr>
                    <h4 class="fw-bold text-primary">Comments:</h4>
                    @foreach($post->comments as $comment)
                        <div class="card p-3 my-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="fw-bold">{{ $comment->user->name }}</p>
                                <p class="lead text-end mb-0">{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</p>
                            </div>
                            <p>{{ $comment->content }}</p>
                            @if(Auth::check() && $comment->user_id == Auth::user()->id)
                                <div class="d-flex justify-content-end">
                                    <a href="#" class="btn btn-sm btn-primary me-2" data-bs-toggle="modal"
                                       data-bs-target="#edit-comment-modal-{{ $comment->id }}">Edit</a>

                                    <div class="modal fade" id="edit-comment-modal-{{ $comment->id }}" tabindex="-1"
                                         aria-labelledby="edit-comment-modal-{{ $comment->id }}-label"
                                         aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="edit-comment-modal-{{ $comment->id }}-label">Edit
                                                        Comment</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('comments.update', $comment->id) }}"
                                                      method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="comment-content">Comment:</label>
                                                            <textarea class="form-control" name="content"
                                                                      id="comment-content"
                                                                      rows="5">{{ $comment->content }}</textarea>
                                                            @error('content')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close
                                                        </button>
                                                        <button type="submit" class="btn btn-primary">Save changes
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                    @if(Auth::check())
                        <button class="btn btn-primary rounded-pill mt-4" id="show-comment-section">Add Comment</button>
                    @endif
                    <div class="mt-4" id="comment-section" style="display: none;">
                        <form action="{{route('comments.store')}}" method="POST">
                            @csrf
                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                            <div class="form-group">
                                <textarea class="form-control" name="content" rows="5"
                                          placeholder="Add your comment here..."></textarea>
                                @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-success rounded-pill mt-2">Comment</button>
                        </form>
                    </div>
                </div>
                @if(Auth::check() && $post->user_id == Auth::user()->id)
                    <div class="card-footer px-5 py-3 d-flex justify-content-end">
                        <a href="{{route('posts.edit', $post->id)}}" class="btn btn-success rounded-pill me-2">Edit</a>
                        <form action="{{route('posts.destroy', $post->id)}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger rounded-pill">Delete</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @section('script')
        <script src="{{asset('js/comments.js')}}"></script>
    @endsection

@endsection
