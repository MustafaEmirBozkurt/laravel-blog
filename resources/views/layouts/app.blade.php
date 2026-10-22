<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('blog.title'))</title>
    <style>
        *{box-sizing:border-box}
        body{margin:0;font-family:system-ui,Arial,sans-serif;background:#f5f1e8;color:#2b2b2b;line-height:1.6}
        header{background:#2f4f2f;color:#fff}
        .wrap{max-width:760px;margin:0 auto;padding:0 16px}
        header .wrap{display:flex;justify-content:space-between;align-items:center;padding-top:14px;padding-bottom:14px}
        header a,header button{color:#fff;text-decoration:none;margin-left:14px;background:none;border:0;font:inherit;cursor:pointer}
        header .brand{font-size:1.3rem;font-weight:700;margin:0}
        main{padding:24px 0 60px}
        .card{background:#fff;border-radius:10px;padding:20px;margin-bottom:16px;box-shadow:0 1px 3px rgba(0,0,0,.08)}
        .card h2{margin:0 0 4px}
        .card h2 a{color:#2f4f2f;text-decoration:none}
        .meta{color:#777;font-size:.85rem;margin-bottom:10px}
        label{display:block;margin:12px 0 4px;font-weight:600}
        input[type=text],input[type=password],textarea{width:100%;padding:10px;border:1px solid #ccc;border-radius:6px;font:inherit}
        textarea{min-height:260px}
        .btn{display:inline-block;padding:9px 16px;border:0;border-radius:6px;background:#2f4f2f;color:#fff;text-decoration:none;cursor:pointer;font:inherit}
        .btn.red{background:#a33}
        .btn.gray{background:#777}
        .alert{padding:10px 14px;border-radius:6px;margin-bottom:16px}
        .ok{background:#dff0d8;color:#2d5a2d}
        .err{background:#f8d7da;color:#842029}
        .actions{display:flex;gap:8px;margin-top:14px}
        .actions form{margin:0}
    </style>
</head>
<body>
<header>
    <div class="wrap">
        <a class="brand" href="{{ route('posts.index') }}" style="margin:0">{{ config('blog.title') }}</a>
        <nav style="display:flex;align-items:center">
            <a href="{{ route('posts.index') }}">Ana Sayfa</a>
            @if(session('blog_auth'))
                <span style="margin-left:14px;opacity:.8">{{ session('blog_user') }}@if(session('blog_role') === 'admin') (yönetici)@endif</span>
                <a href="{{ route('posts.create') }}">Yeni Yazı</a>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;margin:0">
                    @csrf
                    <button type="submit">Çıkış</button>
                </form>
            @else
                <a href="{{ route('login') }}">Giriş</a>
                <a href="{{ route('register') }}">Kayıt Ol</a>
            @endif
        </nav>
    </div>
</header>
<main>
    <div class="wrap">
        @if(session('success'))<div class="alert ok">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert err">{{ session('error') }}</div>@endif
        @if($errors->any())
            <div class="alert err">
                @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            </div>
        @endif
        @yield('content')
    </div>
</main>
</body>
</html>
