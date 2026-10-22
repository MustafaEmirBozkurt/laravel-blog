@extends('layouts.app')
@section('content')
@forelse($posts as $post)
    <div class="card">
        <h2><a href="{{ route('posts.show', $post['id']) }}">{{ $post['title'] }}</a></h2>
        <div class="meta">{{ $post['author'] ?? config('blog.username') }} · {{ \Carbon\Carbon::parse($post['created_at'])->format('d.m.Y H:i') }}</div>
        <p>{{ \Illuminate\Support\Str::limit($post['body'], 200) }}</p>
        <a href="{{ route('posts.show', $post['id']) }}">Devamını oku →</a>
    </div>
@empty
    <div class="card">Henüz yazı yok.@if(session('blog_auth')) <a href="{{ route('posts.create') }}">İlk yazını ekle.</a>@endif</div>
@endforelse
@endsection
