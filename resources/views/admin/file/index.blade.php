@extends('admin.layout')

@section('content')
    <div class="content-padding">
        <h2>Seznam souborů</h2>
        <a class="btn btn-outline-primary" href="{{route('admin.file.create')}}">+ Nahrát nový soubor</a>
        <div class="row">
            <div class="col-xs-12 col-md-9">
                <table class="table table-bordered" id="index_table">
                    <thead>
                        <tr>
                            <th>Jméno souboru</th>
                            <th>Písnička</th>
                            <th>Autor</th>
                            <th>Typ</th>
                            <th>Akce</th>
                        </tr>
                    </thead>
                    @foreach($files as $file)
                    <tr>
                        <td><a href="{{ route('admin.file.edit', $file) }}">{{$file->getPublicName()}}</a></td>
                        <td>
                            @if ($file->song_lyric != null)
                                <a href="{{ route('admin.song.edit', $file->song_lyric) }}">{{$file->song_lyric->name}}</a>
                            @else
                                (nepřiřazeno)
                            @endif
                        </td>
                        <td>
                            @if ($file->author != null)
                                <a href="{{ route('admin.author.edit', $file->author) }}">{{$file->author->name}}</a>
                            @else
                                (bez autora)
                            @endif
                        </td>
                        <td>{{ $file->getTypeString() }}</td>
                        <td class="d-inline-flex">
                            @include('admin.components.deletebutton', [
                                'url' => route('admin.file.delete', $file),
                                'class' => 'btn btn-warning'
                            ])

                            <a class="btn btn-primary" href="{{ route('download.file', $file) }}">STÁHNOUT</a>
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