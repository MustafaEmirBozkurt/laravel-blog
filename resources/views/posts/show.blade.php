@extends('layouts.app')
@section('title', $post['title'])
@section('content')
<div class="card">
    <h2>{{ $post['title'] }}</h2>
    <div class="meta">
        {{ $post['author'] ?? config('blog.username') }} ·
        {{ \Carbon\Carbon::parse($post['created_at'])->format('d.m.Y H:i') }}
        @if($post['updated_at'] !== $post['created_at'])
            · güncellendi: {{ \Carbon\Carbon::parse($post['updated_at'])->format('d.m.Y H:i') }}
        @endif
    </div>
    <div>{!! nl2br(e($post['body'])) !!}</div>

    @if(\App\Support\Access::canManage($post))
        <div class="actions">
            <a class="btn" href="{{ route('posts.edit', $post['id']) }}">Düzenle</a>
            <form method="POST" action="{{ route('posts.destroy', $post['id']) }}" onsubmit="return confirm('Bu yazı silinsin mi?')">
                @csrf @method('DELETE')
                <button class="btn red" type="submit">Sil</button>
            </form>
        </div>
    @endif
</div>
<a href="{{ route('posts.index') }}">← Tüm yazılar</a>
@endsection
