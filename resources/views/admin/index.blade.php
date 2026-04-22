@extends('layouts.app')
@section('title','Админ-панель')
@section('content')
<div class="card"><h1>Пользователи</h1><table><tr><th>ID</th><th>Email</th><th>Роль</th><th>Статус</th><th></th></tr>
@foreach($users as $user)
<tr><td>{{ $user->id }}</td><td>{{ $user->email }}</td><td>{{ $user->role }}</td><td>{{ $user->is_blocked ? 'Blocked' : 'Active' }}</td>
<td><form method="post" action="{{ route('admin.users.toggle-block',$user) }}">@csrf<input name="reason" placeholder="Причина"><button>{{ $user->is_blocked?'Разблокировать':'Блокировать' }}</button></form></td></tr>
@endforeach</table>{{ $users->links() }}</div>
<div class="card mt-16"><h1>Деревья</h1><table><tr><th>ID</th><th>Название</th><th>Владелец</th></tr>@foreach($trees as $tree)<tr><td>{{ $tree->id }}</td><td>{{ $tree->title }}</td><td>{{ $tree->user->email }}</td></tr>@endforeach</table>{{ $trees->links() }}</div>
<div class="card mt-16"><h1>Audit/Security events</h1><table><tr><th>Дата</th><th>Тип</th><th>Subject</th></tr>@foreach($events as $e)<tr><td>{{ $e->created_at }}</td><td>{{ $e->event_type }}</td><td>{{ $e->subject_type }}#{{ $e->subject_id }}</td></tr>@endforeach</table></div>
@endsection
