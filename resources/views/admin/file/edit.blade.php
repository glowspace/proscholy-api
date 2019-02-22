@extends('admin.layout')

@section('content')
    <div class="content-padding">
        <h2>Úprava nahraného souboru</h2>
        <div class="row">
            <div class="col-sm-6">
                <form action="{{route('admin.file.update', ['file' => $file->id])}}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="input-group mb-3">
                        <input class="form-control" required autofocus name="filename" placeholder="Jméno souboru" value="{{ $file->filename }}">
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-append mr-3">
                            <label class="input-group-text">Typ souboru</label>
                        </div>
                        <select class="custom-select" name="type" title="">
                            @foreach($file->type_string as $key => $value)
                                <option value="{{ $key }}" {{ $file->type ===  $key  ? 'selected' : "" }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-append mr-3">
                            <label class="input-group-text">Autor/Interpret</label>
                        </div>

                        @include('admin.components.magicsuggest', [
                            'field_name' => 'assigned_authors',
                            'value_field' => 'id',
                            'display_field' => 'name',
                            'list_all' => $all_authors,
                            'list_selected' => $assigned_authors,
                            'is_single' => true,
                            'disabled' => false
                        ])
                    </div>


                    <div class="input-group mb-3">
                        <div class="input-group-append mr-3">
                            <label>Píseň</label>
                        </div>

                        @include('admin.components.magicsuggest', [
                            'field_name' => 'assigned_song_lyrics',
                            'value_field' => 'id',
                            'display_field' => 'name',
                            'list_all' => $all_song_lyrics,
                            'list_selected' => $assigned_song_lyrics,
                            'is_single' => true,
                            'disabled' => false
                        ])
                    </div>

                    <div class="input-group">
                        <button type="submit" class="btn btn-outline-primary">Uložit</button>
                    </div>
                </form>
                @include('admin.components.deletebutton', [
                    'url' => route('admin.file.delete', ['file' => $file->id]),
                    'class' => 'btn btn-outline-warning',
                    'redirect' => route('admin.file.index')
                ])
            </div>
        </div>
    </div>
@endsection

@include('admin.components.magicsuggest_includes')
@include('admin.components.deletebutton_includes')
