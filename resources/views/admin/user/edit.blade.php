@extends('admin.layout')

@section('content')
    <div class="content-padding">
    <h2>Úprava uživatele</h2>
        <div class="row">
            <div class="col-sm-6">
                <form action="{{ route('admin.user.update', $user) }}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="input-group mb-3">
                        <div class="input-group-append mr-3">
                            <label class="input-group-text">Jméno</label>
                        </div>
                        <input required class="form-control" autofocus name="name" placeholder="zadejte jméno a příjmení" value="{{$user->name}}">
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-append mr-3">
                            <label class="input-group-text">Heslo</label>
                        </div>
                        @if ($user->password == null)
                            <input required class="form-control" autofocus name="new_pass" placeholder="zadejte nové heslo">
                        @else
                            <input class="form-control" autofocus name="new_pass" placeholder="(nezměněno)">
                        @endif
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-append mr-3">
                            <label class="input-group-text">Role</label>
                        </div>
                        <select class="custom-select" name="role">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ $user->hasRole($role)  ? 'selected' : "" }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="input-group">
                        <label class="input-group-text">Povolit správu těchto autorů: (pouze pro autorský účet)</label>
                    </div>
                    @include('admin.components.magicsuggest', [
                        'field_name' => 'assigned_authors',
                        'value_field' => 'id',
                        'display_field' => 'name',
                        'list_all' => $all_authors,
                        'list_selected' => $assigned_authors,
                        'is_single' => false,
                        'allow_free_entries' => false,
                        'disabled' => false
                    ])
                    
                    <br/>

                    <div class="input-group">
                        <button type="submit" class="btn btn-outline-primary">Uložit</button>
                    </div>
                </form>

                @include('admin.components.deletebutton', [
                    'url' => route('admin.user.destroy', $user),
                    'class' => 'btn btn-outline-warning',
                    'redirect' => route('admin.user.index')
                ])
            </div>
        </div>
    </div>
@endsection

