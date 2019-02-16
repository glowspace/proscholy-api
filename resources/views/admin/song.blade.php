@extends('layout.layout')

@section('content')
    <div class="content-padding">

        @if(empty($song))
            <h2>Nová píseň</h2>

            <form action="{{route('admin.song.new.save')}}" method="post">
                @csrf
                <input autofocus name="name" placeholder="originální název písně"><br>
                <i>Pozor jestli to není překlad</i><br>

                <input name="translation_name"
                    placeholder="jméno prvního překladu (pokud nějaký existuje a chcete jej vytvořit)">

                <input type="submit">
            </form>
        @else
            <h2>Úprava písně</h2>

            <div class="row">
                <div class="col-sm-4 offset-4">
                    <form action="{{route('admin.song.edit.save')}}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{$song->id}}">

                        <label>Název</label>
                        <input class="form-control" autofocus name="name" placeholder="název písně" value="{{$song->name}}"><br>

                        <label>Společné ID písně</label>
                        <input class="form-control" autofocus type="number" name="song_id" placeholder="název písně"
                            value="{{$song->song_id}}"><br>

                        <label>Autoři</label><br>
                        @foreach($song->authors as $author)
                            {{$author->name}}<br>
                        @endforeach

                        <a class="btn btn-primary" href="{{route('admin.song.author.add',['id'=>$song->id])}}">Přidat autora</a>
                        <br><br>

                        <label>Text</label>
                        <textarea rows="20" name="lyrics" class="form-control" title="">{{$song->lyrics}}</textarea>

                        <br>
                        <label>Typ</label>
                        <select class="form-control" name="is_original" title="">
                            <option value="1" @if($song->is_original)
                            selected
                                    @endif>Originál
                            </option>
                            <option value="0" @if(!$song->is_original)
                            selected
                                    @endif>Překlad
                            </option>
                        </select>

                        <br>
                        <label>Autorizovaný překlad</label>
                        <select class="form-control" name="is_original" title="">
                            <option value="1" @if($song->is_original)
                            selected
                                    @endif>Autorizovaný překlad nebo originál
                            </option>
                            <option value="0" @if(!$song->is_original)
                            selected
                                    @endif>Ne
                            </option>
                        </select>

                        <br>

                        <input class="btn btn-primary" type="submit" value="Uložit">
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection
