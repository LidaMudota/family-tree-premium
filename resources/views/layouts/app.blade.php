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
<header class="topbar">
    <div class="container nav-wrap">
        <a href="{{ route('home') }}" class="brand">Family Tree Premium</a>
        <nav>
            <a href="{{ route('how') }}">Как это работает</a>
            <a href="{{ route('faq') }}">FAQ</a>
            <a href="{{ route('contact') }}">Контакты</a>
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
<main class="container py-32">
    @if (session('status'))<div class="alert success">{{ session('status') }}</div>@endif
    @if ($errors->any())<div class="alert danger">{{ $errors->first() }}</div>@endif
    @yield('content')
</main>
<footer class="container footer">
    <a href="{{ route('privacy') }}">Политика</a>
    <a href="{{ route('terms') }}">Условия</a>
    <a href="{{ route('support') }}">Поддержка</a>
</footer>
</body>
</html>
