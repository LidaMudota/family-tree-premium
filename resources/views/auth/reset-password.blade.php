@extends('layouts.app')
@section('content')
<div class="card form-card"><h1>Сброс пароля</h1><form method="post" action="{{ route('password.update') }}">@csrf
<input type="hidden" name="token" value="{{ $token }}">
<label>Email<input type="email" name="email" value="{{ $email }}" required></label>
<label>Пароль<input type="password" name="password" required></label>
<label>Подтверждение<input type="password" name="password_confirmation" required></label>
<button class="btn-accent">Сменить пароль</button></form></div>
@endsection
