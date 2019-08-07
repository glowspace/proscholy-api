@extends('layout.admin')

@section('content-withmenu')
    <div class="__container-fluid">
        <h2>Nahrát nový soubor</h2>

        <div class="row">
            <div class="col-sm-6">
                    
                <a href="{{route('admin.file.index')}}">Zpět do administrace</a>
                {{-- <a href="{{route('admin.todo')}}">Zpět na TO-DO list</a> --}}
        
                <form action="{{route('admin.file.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group mb-3">
                        <input class="form-control" type="file" required autofocus name="filename">
                    </div>
                    @if (isset($song_lyric))
                        <input type="hidden" name="song_lyric_id" value="{{ $song_lyric->id }}"> 
                    @endif
        
                    <div class="input-group">
                        <button type="submit" name="redirect" value="edit" class="btn btn-outline-primary">Uložit a upravit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
