@extends('layouts.app')
@section('title', 'Kayıt Ol')
@section('content')
<div class="card">
    <h2>Kayıt Ol</h2>
    <form method="POST" action="{{ route('register.post') }}">
        @csrf
        <label for="username">Kullanıcı adı</label>
        <input type="text" id="username" name="username" value="{{ old('username') }}" maxlength="30" required autofocus>
        <label for="password">Şifre (en az 8 karakter)</label>
        <input type="password" id="password" name="password" required>
        <label for="password_confirmation">Şifre tekrar</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required>
        <div class="actions"><button class="btn" type="submit">Kayıt Ol</button></div>
    </form>
    <p class="meta" style="margin-top:14px">Zaten hesabın var mı? <a href="{{ route('login') }}">Giriş yap</a></p>
</div>
@endsection
