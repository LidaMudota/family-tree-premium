@extends('layouts.app')
@section('title','Вход')
@section('content')
<div class="card form-card"><h1>Вход</h1>
<form method="post" action="{{ route('login.attempt') }}">@csrf
<label>Email<input type="email" name="email" required value="{{ old('email') }}"></label>
<label>Пароль<input type="password" name="password" required></label>
<label><input type="checkbox" name="remember"> Запомнить меня</label>
<button class="btn-accent">Войти</button>
<a href="{{ route('password.request') }}">Забыли пароль?</a>
</form></div>
@endsection
