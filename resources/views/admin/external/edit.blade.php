@extends('admin.layout')

@section('content')
    <div class="content-padding">
        <h2>Úprava externího zdroje</h2>
            
        {{-- <a href="{{route('admin.externals')}}">Zpět do administrace</a>
        <a href="{{route('admin.todo')}}">Zpět na TO-DO list</a> --}}

        <div class="row">
            <div class="col-sm-6">
                {!! $external->getHtml() !!}

                <form action="{{route('admin.external.update', ['external' => $external->id])}}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="input-group mb-3">
                        <input class="form-control" required autofocus name="url" placeholder="Adresa URL zdroje" value="{{ $external->url }}">
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-append mr-3">
                            <label class="input-group-text">Typ odkazu</label>
                        </div>
                        <select class="custom-select" name="type" title="">
                            @foreach($external->type_string as $value)
                                <option value="{{$loop->iteration}}" {{ $external->type == $loop->iteration ? 'selected' : "" }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-append mr-3">
                            <label class="input-group-text">Autor odkazu</label>
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
            </div>
            {{-- <div class="col-sm-4">
                <iframe height="500" src="{{route('admin.external.edit.author',['id'=>$external->id])}}"></iframe>
            </div>
            <div class="col-sm-4">
                <iframe height="500" src="{{route('admin.external.edit.translation',['id'=>$external->id])}}"></iframe>
            </div> --}}
        </div>
    </div>
@endsection

@include('admin.components.magicsuggest_includes')
