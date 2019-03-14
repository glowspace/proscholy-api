@extends('admin.layout')

@section('content')
    <div class="content-padding">
        <h2>Seznam souborů</h2>
        <a class="btn btn-outline-primary" href="{{route('admin.file.create')}}">+ Nahrát nový soubor</a>
        <div class="row">
            <div class="col-xs-12 col-md-9">
                @component('admin.components.table', [
                    'id' => 'index_table',
                    'columns' => ['Jméno', 'Email', 'Role', 'Akce']
                ])
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
                            ])

                            <a class="btn btn-primary" href="{{ $file->download_url }}">STÁHNOUT</a>
                        </td>
                    </tr>
                    @endforeach
            @endcomponent
            </div>
        </div>
    </div>
@endsection

