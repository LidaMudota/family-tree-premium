<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>PDF export</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif }
        .grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px }
        .card { border: 1px solid #ddd; padding: 12px; border-radius: 10px }
    </style>
    <script src="{{ asset('js/print.js') }}" defer></script>
</head>
<body class="js-auto-print">
<h1>{{ $tree->title }}</h1>
<p>Экспортный вид. Используйте "Печать" -> "Сохранить как PDF".</p>
<div class="grid">@foreach($tree->people as $p)<div class="card"><strong>{{ $p->displayName() }}</strong><br>{{ $p->summary_note }}</div>@endforeach</div>
</body>
</html>
