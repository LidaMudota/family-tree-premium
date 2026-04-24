@extends('layouts.app')
@section('title', 'Редактор дерева: '.$tree->title)
@section('content')
<div class="card">
<div class="toolbar">
<h1>{{ $tree->title }}</h1>
<input id="searchPerson" placeholder="Поиск по персоне..." aria-label="Поиск">
<button id="fitBtn">Fit</button><button id="centerBtn">Center</button>
<button id="exportPngBtn" class="btn-accent">PNG</button>
<a href="{{ route('trees.export.pdf',$tree) }}" target="_blank">PDF</a>
</div>
<div id="tree-canvas-wrap"><svg id="treeSvg" width="1400" height="900" aria-label="Семейное дерево"></svg></div>
</div>
<div class="grid2 mt-16">
<div class="card"><h3>Добавить/редактировать персону</h3>
<form method="post" action="{{ route('people.store',$tree) }}" enctype="multipart/form-data">@csrf
@include('tree.person-fields')
<button class="btn-accent">Сохранить</button>
</form></div>
<div class="card"><h3>Добавить связь</h3>
<form method="post" action="{{ route('relationships.store',$tree) }}">@csrf
<label>Персона<select name="person_id">@foreach($people as $p)<option value="{{ $p->id }}">{{ $p->displayName() }}</option>@endforeach</select></label>
<label>Родственник<select name="relative_id">@foreach($people as $p)<option value="{{ $p->id }}">{{ $p->displayName() }}</option>@endforeach</select></label>
<label>Тип<select name="type"><option>father</option><option>mother</option><option>brother</option><option>sister</option><option>partner</option><option>child</option></select></label>
<button>Добавить связь</button>
</form></div>
</div>
<script>
window.familyTreeData = {
    treeId: {{ $tree->id }},
    csrf: '{{ csrf_token() }}',
    viewport: @json($tree->viewport),
        people: @js($people->map(fn($p)=>['id'=>$p->id,'name'=>$p->displayName(),'summary'=>$p->summary_note,'photo'=>$p->photo_path ? route('people.photo', [$tree, $p]) : null])->values()),
    links: @json($relationships->map(fn($r)=>['source'=>$r->person_id,'target'=>$r->relative_id,'type'=>$r->type]))
}
</script>
@endsection
