@extends('layouts.app')
@section('title', 'Редактор дерева: '.$tree->title)

@section('content')
<div class="card tree-builder" id="treeBuilder" data-state="ready">
    <div class="toolbar tree-toolbar">
        <div>
            <h1>{{ $tree->title }}</h1>
            <p id="treeState" class="muted">Загрузка дерева...</p>
        </div>
        <div class="toolbar-actions">
            <button type="button" class="btn-accent" data-open-person-modal>Добавить персону</button>
            <a href="{{ route('trees.export.pdf', $tree) }}" target="_blank">PDF</a>
        </div>
    </div>

    <div id="personFormAlert" class="alert" hidden></div>

    <section class="people-grid" id="peopleCards" aria-live="polite"></section>
</div>

<div class="card mt-16">
    <h3>Связи между персонами</h3>
    @if($people->isEmpty())
        <p class="muted">Сначала добавьте хотя бы двух персон, затем создавайте связи.</p>
    @else
        <form method="post" action="{{ route('relationships.store', $tree) }}" class="relationship-form">@csrf
            <label>Персона
                <select name="person_id" required>
                    @foreach($people as $p)
                        <option value="{{ $p->id }}">{{ $p->displayName() }}</option>
                    @endforeach
                </select>
            </label>
            <label>Родственник
                <select name="relative_id" required>
                    @foreach($people as $p)
                        <option value="{{ $p->id }}">{{ $p->displayName() }}</option>
                    @endforeach
                </select>
            </label>
            <label>Тип
                <select name="type" required>
                    <option>father</option>
                    <option>mother</option>
                    <option>brother</option>
                    <option>sister</option>
                    <option>partner</option>
                    <option>child</option>
                </select>
            </label>
            <button type="submit">Добавить связь</button>
        </form>
    @endif
</div>

<div class="person-modal" id="personModal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="personModalTitle">
    <div class="person-modal__dialog">
        <div class="person-modal__header">
            <h2 id="personModalTitle">Новая персона</h2>
            <button type="button" class="person-modal__close" data-close-person-modal aria-label="Закрыть">×</button>
        </div>

        <form id="personCreateForm" method="post" action="{{ route('people.store', $tree) }}" enctype="multipart/form-data" novalidate>
            @csrf

            <label>Имя *
                <input name="first_name" required autocomplete="given-name">
                <small id="error_first_name" class="field-error" hidden></small>
            </label>

            <label>Фамилия
                <input name="last_name" autocomplete="family-name">
                <small id="error_last_name" class="field-error" hidden></small>
            </label>

            <label>Дата рождения
                <input type="date" name="birth_date">
                <small id="error_birth_date" class="field-error" hidden></small>
            </label>

            <label>Краткое описание
                <textarea name="summary_note" rows="3" maxlength="240" placeholder="Кто это в вашей семье"></textarea>
                <small id="error_summary_note" class="field-error" hidden></small>
            </label>

            <label>Фото
                <input type="file" name="photo" accept="image/png,image/jpeg,image/webp">
                <small id="error_photo" class="field-error" hidden></small>
            </label>

            <div class="person-modal__actions">
                <button type="button" data-close-person-modal>Отмена</button>
                <button type="submit" class="btn-accent">Сохранить</button>
            </div>
        </form>
    </div>
</div>

<div
    id="familyTreeData"
    hidden
    data-tree-id="{{ $tree->id }}"
    data-csrf="{{ csrf_token() }}"
    data-people='@js($people->map(fn($p) => ["id" => $p->id, "name" => $p->displayName(), "summary" => $p->summary_note, "birthDate" => optional($p->birth_date)->format("Y-m-d"), "photo" => $p->photo_path ? route("people.photo", [$tree, $p]) : null])->values())'
        'id' => $p->id,
        'name' => $p->displayName(),
        'summary' => $p->summary_note,
        'birthDate' => optional($p->birth_date)->format('Y-m-d'),
        'photo' => $p->photo_path ? route('people.photo', [$tree, $p]) : null,
    ])->values())'
    data-links='@json($relationships->map(fn($r) => ['source' => $r->person_id, 'target' => $r->relative_id, 'type' => $r->type])->values())'
></div>
@endsection
