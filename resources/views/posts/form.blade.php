@extends('layouts.app')
@section('title', $post ? 'Yazıyı Düzenle' : 'Yeni Yazı')
@section('content')
<div class="card">
    <h2>{{ $post ? 'Yazıyı Düzenle' : 'Yeni Yazı' }}</h2>
    <form method="POST" action="{{ $post ? route('posts.update', $post['id']) : route('posts.store') }}">
        @csrf
        @if($post) @method('PUT') @endif
        <label for="title">Başlık</label>
        <input type="text" id="title" name="title" maxlength="150" value="{{ old('title', $post['title'] ?? '') }}" required>
        <label for="body">İçerik</label>
        <textarea id="body" name="body" required>{{ old('body', $post['body'] ?? '') }}</textarea>
        <div class="actions">
            <button class="btn" type="submit">Kaydet</button>
            <a class="btn gray" href="{{ $post ? route('posts.show', $post['id']) : route('posts.index') }}">Vazgeç</a>
        </div>
    </form>
</div>
@endsection
