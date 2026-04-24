<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Family Tree Premium')</title>
    <meta name="description" content="@yield('description', 'Премиум сервис семейного дерева')">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Family Tree Premium')">
    <meta property="og:description" content="@yield('description', 'Премиум сервис семейного дерева')">
    <meta property="og:type" content="website">
    @auth <meta name="robots" content="noindex,nofollow"> @else <meta name="robots" content="index,follow"> @endauth
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="app-bg">
<div class="site-shell">
    <div class="ambient ambient-a"></div>
    <div class="ambient ambient-b"></div>
    <div class="ambient ambient-c"></div>

    <header class="topbar">
        <div class="container nav-wrap">
            <a href="{{ route('home') }}" class="brand">
                <span class="brand-dot"></span>
                <span>Family Tree Premium</span>
            </a>
            <nav>
                @auth
                    <a href="{{ route('dashboard') }}">Кабинет</a>
                    @if(auth()->user()->isAdmin())<a href="{{ route('admin.index') }}">Админ</a>@endif
                    <form method="post" action="{{ route('logout') }}" class="inline">@csrf<button>Выйти</button></form>
                @else
                    <a href="{{ route('login') }}">Вход</a>
                    <a href="{{ route('register') }}" class="btn-accent">Регистрация</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="container page-content py-32">
        @if (session('status'))<div class="alert success" data-reveal>{{ session('status') }}</div>@endif
        @if ($errors->any())<div class="alert danger" data-reveal>{{ $errors->first() }}</div>@endif
        @yield('content')
    </main>

    <footer class="footer-wrap">
        <div class="container footer">
            <div>
                <div class="brand footer-brand"><span class="brand-dot"></span><span>Family Tree Premium</span></div>
                <p>Цифровой архив родовых историй с безопасным хранением, экспортом и совместной работой.</p>
            </div>
            <div class="footer-links">
                <a href="{{ route('privacy') }}">Политика</a>
                <a href="{{ route('terms') }}">Условия</a>
                <a href="{{ route('support') }}">Поддержка</a>
            </div>
        </div>
    </footer>
</div>
</body>
</html>
