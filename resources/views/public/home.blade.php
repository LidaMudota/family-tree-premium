@extends('layouts.app')
@section('title','Family Tree Premium — семейный архив')
@section('description','Премиальная платформа для сохранения семейной истории: дерево, биографии, медиа и экспорт архива.')
@section('content')
<section class="hero hero-grid">
    <div data-reveal>
        <span class="eyebrow">Digital Heritage Platform</span>
        <h1>Семейное дерево как
            <span class="accent-text">живое цифровое наследие</span>
        </h1>
        <p>Собирайте биографии, фото и связи в единый семейный архив. Создавайте структуру поколений, сохраняйте историю и экспортируйте результат в PNG/PDF.</p>
        <div class="hero-actions">
            <a class="btn-accent" href="{{ route('register') }}">Начать бесплатно</a>
        </div>
        <div class="hero-metrics">
            <div><strong>∞</strong><span>ветвей и поколений</span></div>
            <div><strong>PNG/PDF</strong><span>экспорт архива</span></div>
            <div><strong>24/7</strong><span>доступ к данным</span></div>
        </div>
    </div>

    <div class="hero-showcase" data-reveal>
        <div class="show-card floating-card">
            <small>Timeline Snapshot</small>
            <h3>Родовая карта</h3>
            <p>Фокус на ключевых людях, визуальные связи и контекстные заметки.</p>
        </div>
        <div class="show-card muted-card">
            <small>Security Layer</small>
            <h3>Контроль доступа</h3>
            <p>Приватность по умолчанию с безопасными пользовательскими сценариями.</p>
        </div>
    </div>
</section>

<section class="feature-grid">
    <article class="card" data-reveal>
        <h3>Глубокий контекст персон</h3>
        <p>Добавляйте краткие биографии, факты, даты и связи для каждого члена семьи.</p>
    </article>
    <article class="card" data-reveal>
        <h3>Умная визуализация</h3>
        <p>Редактор дерева с масштабированием, перемещением и быстрым поиском по персонам.</p>
    </article>
    <article class="card" data-reveal>
        <h3>Архив и переносимость</h3>
        <p>Экспортируйте дерево в изображения и документы для офлайн-архива и семейных встреч.</p>
    </article>
</section>
@endsection
