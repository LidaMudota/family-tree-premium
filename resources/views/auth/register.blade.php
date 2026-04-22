@extends('layouts.app')
@section('title','Регистрация')
@section('content')
<div class="card form-card"><h1>Регистрация</h1>
<form method="post" action="{{ route('register.store') }}">@csrf
<label>Имя<input name="name" required></label>
<label>Email<input type="email" name="email" required></label>
<label>Пароль<input type="password" name="password" required></label>
<label>Подтверждение<input type="password" name="password_confirmation" required></label>
<button class="btn-accent">Создать аккаунт</button>
</form></div>
@endsection
