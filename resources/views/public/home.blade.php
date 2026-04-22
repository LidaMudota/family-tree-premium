@extends('layouts.app')
@section('title','Family Tree Premium — семейный архив')
@section('content')
<section class="hero">
    <h1>Семейное дерево как цифровое наследие</h1>
    <p>Создавайте визуальные деревья, храните факты, фото и экспортируйте архив в PNG/PDF.</p>
    <a class="btn-accent" href="{{ route('register') }}">Начать бесплатно</a>
</section>
@endsection
