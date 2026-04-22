@extends('layouts.app')
@section('title','Кабинет')
@section('content')
<div class="grid2"><div class="card"><h1>Кабинет</h1><p>Деревьев: {{ $treesCount }}</p><p>Персон: {{ $peopleCount }}</p><a class="btn-accent" href="{{ route('trees.index') }}">Открыть деревья</a></div></div>
@endsection
