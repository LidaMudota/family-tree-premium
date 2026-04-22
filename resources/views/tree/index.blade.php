@extends('layouts.app')
@section('title','Мои деревья')
@section('content')
<div class="card">
<h1>Мои деревья</h1>
<form method="post" action="{{ route('trees.store') }}" class="inline-form">@csrf
<input name="title" required placeholder="Название дерева">
<input name="description" placeholder="Описание">
<button class="btn-accent">Создать</button>
</form>
<div class="grid2 mt-16">
@forelse($trees as $tree)
<div class="card">
<h3>{{ $tree->title }}</h3><p>{{ $tree->description }}</p>
<a href="{{ route('trees.show',$tree) }}">Открыть редактор</a>
<form method="post" action="{{ route('trees.archive',$tree) }}">@csrf<button>Архивировать</button></form>
<form method="post" action="{{ route('trees.destroy',$tree) }}">@csrf @method('delete')<button>Удалить</button></form>
</div>
@empty <div class="empty">Пока нет деревьев.</div> @endforelse
</div>
{{ $trees->links() }}
</div>
@endsection
