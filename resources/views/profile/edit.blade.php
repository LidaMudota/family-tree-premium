@extends('layouts.app')
@section('title','Профиль и безопасность')
@section('content')
<div class="grid2">
<div class="card"><h2>Профиль</h2><form method="post" action="{{ route('profile.update') }}">@csrf @method('patch')
<label>Имя<input name="name" value="{{ auth()->user()->name }}" required></label>
<label>Email<input name="email" value="{{ auth()->user()->email }}" required></label>
<button class="btn-accent">Сохранить</button></form></div>
<div class="card"><h2>Безопасность</h2><form method="post" action="{{ route('profile.password') }}">@csrf @method('patch')
<label>Текущий пароль<input type="password" name="current_password" required></label>
<label>Новый пароль<input type="password" name="password" required></label>
<label>Подтверждение<input type="password" name="password_confirmation" required></label>
<button>Сменить пароль</button></form>
<form method="post" action="{{ route('profile.destroy') }}" class="mt-16">@csrf @method('delete')<label>Подтвердите пароль<input type="password" name="password" required></label><button>Удалить аккаунт</button></form>
</div></div>
@endsection
