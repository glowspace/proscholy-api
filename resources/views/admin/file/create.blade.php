@extends('layout.admin')

@section('title-suffixed', 'Nahrát nový soubor')

@section('content-withmenu')
    <div class="__container-fluid">
        <h1 class="h2 mb-3">Nahrát nový soubor
            @if (isset($song_lyric))
            k písni <a href="{{ route('admin.song.edit', $song_lyric) }}">{{ $song_lyric->name }}</a>
            @endif
        </h1>

        <div class="row">
            <div class="col-sm-6">
                <form action="{{route('admin.file.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group mb-3">
                        <input class="form-control" type="file" required autofocus name="filename">
                    </div>
                    @if (isset($song_lyric))
                        <input type="hidden" name="song_lyric_id" value="{{ $song_lyric->id }}">
                    @endif

                    <div class="input-group">
                        <button type="submit" name="redirect" value="edit" class="btn btn-outline-primary mr-2">Uložit a upravit</button>
                        <button type="submit" name="redirect" value="create" class="btn btn-outline-primary">Uložit a nahrát další soubor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
