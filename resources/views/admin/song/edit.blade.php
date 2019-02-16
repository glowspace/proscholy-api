@extends('layout.layout')

@section('content')
    <div class="content-padding">
        <h2>Úprava písně</h2>

        <div class="row">
            <div class="col-sm-4 offset-4">
                <form action="{{ route('admin.song.update', ['song_lyric' => $song_lyric->id]) }}" method="post">
                    @csrf
                    @method('PUT')
                    
                    <input type="hidden" name="id" value="{{$song_lyric->id}}">

                    <label>Název</label>
                    <input class="form-control" autofocus name="name" placeholder="název písně" value="{{$song_lyric->name}}"><br>

                    <label>Autoři</label><br>
                    {{-- @foreach($song_lyric->authors as $author)
                        {{$author->name}}<br>
                    @endforeach --}}

                    <input id="authors_magicsuggest" class="form-control" name="authors[]"/>

                    {{-- <a class="btn btn-primary" href="{{route('admin.song.author.add',['id'=>$song_lyric->id])}}">Přidat autora</a> --}}
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

                    <input class="btn btn-primary" type="submit" value="Uložit">
                </form>
            </div>
        </div>
    </div>
@endsection

@push('head_links')
    <link href="{{asset('css/magicsuggest-min.css')}}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{asset('js/magicsuggest-min.js')}}"></script>

    <script>
        $(function() {
            Object.getPrototypeOf($('#authors_magicsuggest')).size = function () { return this.length; }
            $('#authors_magicsuggest').magicSuggest({
                data: @json(\App\Author::select(['id', 'name'])->get()),
                valueField: 'id',
                displayField: 'name',
                allowFreeEntries: false,
                renderer: function(data){
                    return data.name;
                },
            });
        });
    </script>
@endpush