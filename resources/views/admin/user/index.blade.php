@extends('layout.admin')

@section('content-withmenu')
    <div class="__container-fluid">
        <h2>Seznam uživatelů</h2>
        <a class="btn btn-outline-primary" href="{{route('admin.user.create')}}">+ Nový uživatel</a>
        <div class="row mt-3">
            <div class="col-xs-12 col-md-8">
                @component('admin.components.table', [
                    'id' => 'index_table',
                    'columns' => ['Jméno', 'E-mail', 'Role', 'Akce'],
                    'class' => 'card d-table'
                ])
                    @foreach($users as $user)
                    <tr>
                        <td><a href="{{route('admin.user.edit', $user)}}">{{ $user->name }}</a></td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->roles->count() > 0 ? $user->roles->first()->name : "-" }}</td>
                        <td>
                            @include('admin.components.deletebutton', [
                                'url' => route('admin.user.destroy', $user),
                                'class' => 'text-warning'
                            ])
                        </td>
                    </tr>
                    @endforeach
                @endcomponent
            </div>
        </div>
    </div>
@endsection

