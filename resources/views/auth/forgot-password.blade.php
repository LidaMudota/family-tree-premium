@extends('layouts.app')
@section('content')<div class="card form-card"><h1>Восстановление</h1><form method="post" action="{{ route('password.email') }}">@csrf<label>Email<input type="email" name="email" required></label><button class="btn-accent">Отправить ссылку</button></form></div>@endsection
