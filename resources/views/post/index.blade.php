@extends('layouts.app')
@section('title', 'Home Page')
@section('heading', 'All Posts')
@section('link_text', 'Add New Post')
@section('link', '/posts/create')
@section('content')

    @if(session('message'))
        <div class="alert alert-{{ session('status') }} alert-dismissible fade show" role="alert">
            <strong>{{ session('message') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="container">
        <div class="row justify-content-center mt-1">
            @forelse($posts as $key => $row)
                <div class="col-lg-8 mt-4">
                    <div class="card shadow">
                        <a href="{{route('posts.show', $row->id)}}">
                            <img src="{{ asset('images/'.$row->image) }}" class="card-img-top img-fluid" style="width: 100%; height: 500px; object-fit: cover;">
                        </a>
                        <div class="card-body">
                            <p class="btn btn-success rounded-pill btn-sm">{{ $row->category }}</p>
                            <p class="lead">{{ \Carbon\Carbon::parse($row->created_at)->diffForHumans() }}</p>
                            <div class="card-title fw-bold text-primary h4">{{ $row->title }}</div>
                            <p class="text-secondary">{{ Str::limit($row->content, 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div class="fw-bold text-primary">
                                    Posted by {{ $row->user->name }}
                                </div>
                                <div class="fw-bold text-primary">
                                    {{ $row->comments->count() }} {{ Str::plural('Comment', $row->comments->count()) }}
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('posts.show', $row->id) }}" class="btn btn-info">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-lg-12">
                    <div class="alert alert-danger">No posts found.</div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
