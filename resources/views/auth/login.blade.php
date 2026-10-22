@extends('layouts.app')
@section('title', 'Giriş')
@section('content')
<div class="card">
    <h2>Giriş Yap</h2>
    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <label for="username">Kullanıcı adı</label>
        <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus>
        <label for="password">Şifre</label>
        <input type="password" id="password" name="password" required>
        <div class="actions"><button class="btn" type="submit">Giriş</button></div>
    </form>
    <p class="meta" style="margin-top:14px">Hesabın yok mu? <a href="{{ route('register') }}">Kayıt ol</a></p>
</div>
@endsection
