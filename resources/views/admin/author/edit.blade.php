@extends('layout.admin')

@section('content')
    <div class="content-padding">
    <h2>Úprava autora</h2>
        <div class="row">
            <div class="col-sm-6">
                <form action="{{ route('admin.author.update', ['author' => $author->id]) }}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="input-group mb-3">
                        <input required class="form-control" autofocus name="name" placeholder="jméno autora" value="{{$author->name}}">
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-append mr-3">
                            <label class="input-group-text">Typ autora</label>
                        </div>
                        <select class="custom-select" name="type" title="">
                            @foreach($author->type_string_values as $key => $value)
                                <option value="{{ $key }}" {{ $author->type ===  $key  ? 'selected' : "" }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <label>Informace k autorovi</label>
                    <textarea rows="20" name="description" class="form-control" title="">{{$author->description}}</textarea>
                    <br/>

                    <div class="input-group">
                        <button type="submit" class="btn btn-outline-primary">Uložit</button>
                    </div>
                </form>

                @include('admin.components.deletebutton', [
                    'url' => route('admin.author.destroy', $author),
                    'class' => 'btn btn-outline-warning',
                    'redirect' => route('admin.author.index')
                ])
            </div>
            <div class="col-sm-6">
                @if ($author->songOriginalLyrics()->count())
                    <h5>Přehled autorových písní - originály</h5>
                    <ul>
                        @foreach ($author->songOriginalLyrics()->get() as $song_lyric)
                            <li><a href="{{ route('admin.song.edit', ['song_lyric' => $song_lyric->id]) }}" target="_blank">{{ $song_lyric->name }}</a></li>
                        @endforeach
                    </ul>
                @endif
                @if ($author->songNotOriginalLyrics()->count())
                    <h5>Přehled autorových písní - překlady</h5>
                    @foreach ($author->songNotOriginalLyrics()->get() as $song_lyric)
                        <li><a href="{{ route('admin.song.edit', ['song_lyric' => $song_lyric->id]) }}" target="_blank">{{ $song_lyric->name }}</a></li>
                    @endforeach
                @endif
                @if ($author->externals->count() + $author->files->count())
                    <h5>Přehled všech autorských materiálů</h5>
                    <ul>
                        @foreach ($author->externals as $external)
                            <li>Externí odkaz ({{ $external->type_string }}): <a target="_blank" href="{{ route('admin.external.edit', $external) }}">{{ $external->url }}</a></li>                    
                        @endforeach
                        @foreach ($author->files as $file)
                            <li>Soubor ({{ $file->type_string }}): <a target="_blank" href="{{ route('admin.file.edit', $file) }}">{{$file->public_name}}</a></li>                    
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
@endsection

