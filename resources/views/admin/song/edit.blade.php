@extends('layout.layout')

@section('content')
    <div class="content-padding">
        <h2>Úprava písně</h2>

        <div class="row">
            <div class="col-sm-12">
                <form action="{{ route('admin.song.update', ['song_lyric' => $song_lyric->id]) }}" method="post">
                    @csrf
                    @method('PUT')
                    
                    <input type="hidden" name="id" value="{{$song_lyric->id}}">

                    <label>Název</label>
                    <input class="form-control" autofocus name="name" placeholder="název písně" value="{{$song_lyric->name}}"><br>

                    <label>Autoři</label><br>

                    @include('admin.components.magicsuggest', [
                        'field_name' => 'authors',
                        'value_field' => 'id',
                        'display_field' => 'name',
                        'list_all' => $all_authors,
                        'list_selected' => $assigned_authors,
                        'is_single' => false
                    ])

                    <br><br>

                    <label>Text</label>
                    <textarea rows="20" name="lyrics" class="form-control" title="">{{$song_lyric->lyrics}}</textarea>

                    <br>
                    <label>Typ</label>
                    <select class="form-control" name="is_original" title="">
                        <option value="1" @if($song_lyric->is_original)
                        selected
                                @endif>Originál
                        </option>
                        <option value="0" @if(!$song_lyric->is_original)
                        selected
                                @endif>Překlad
                        </option>
                    </select>

                    <br>
                    <label>Autorizovaný překlad</label>
                    <select class="form-control" name="is_original" title="">
                        <option value="1" @if($song_lyric->is_original)
                        selected
                                @endif>Autorizovaný překlad nebo originál
                        </option>
                        <option value="0" @if(!$song_lyric->is_original)
                        selected
                                @endif>Ne
                        </option>
                    </select>

                    <br>

                    <input class="btn btn-outline-primary" type="submit" value="Uložit">
                </form>
            </div>
        </div>
    </div>
@endsection

@include('admin.components.magicsuggest_includes')

    {{-- <script>
        $(function() {
            Object.getPrototypeOf($('#authors_magicsuggest')).size = function () { return this.length; }
            $('#authors_magicsuggest').magicSuggest({
                data: @json($all_authors),
                valueField: 'id',
                displayField: 'name',
                allowFreeEntries: true,
                value: @json($assigned_authors),
                // renderer: function(data){
                //     return data.name;
                // },
            });
        });
    </script> --}}