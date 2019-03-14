@extends('admin.layout')

@section('content')
    <div class="content-padding">
        <h2>Seznam uživatelů</h2>
        <a class="btn btn-outline-primary" href="{{route('admin.user.create')}}">+ Nový uživatel</a>
        <div class="row">
            <div class="col-xs-12 col-md-8">
                <table class="table table-bordered" id="index_table">
                    <thead>
                        <tr>
                            <th>Jméno</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Akce</th>
                        </tr>
                    </thead>
                    @foreach($users as $user)
                    <tr>
                        <td><a href="{{route('admin.user.edit', $user)}}">{{ $user->name }}</a></td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->roles->count() > 0 ? $user->roles->first()->name : "-" }}</td>
                        <td>
                            @include('admin.components.deletebutton', [
                                'url' => route('admin.user.delete', $user),
                            ])
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection

@include('admin.components.deletebutton_includes')

@include('admin.components.datatable_includes')
@include('admin.components.datatable', ['table_id' => 'index_table'])