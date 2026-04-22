@extends('layouts.app')
@section('content')
<div class="card"><h1>Подтвердите email</h1><p>Мы отправили письмо для подтверждения.</p>
<form method="post" action="{{ route('verification.send') }}">@csrf<button class="btn-accent">Отправить повторно</button></form></div>
@endsection
