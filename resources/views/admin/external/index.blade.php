@extends('admin.layout')

@section('content')
    <div class="content-padding">
            <h2>{{ $title ?? "Seznam externích zdrojů"}}</h2>
        <a class="btn btn-outline-primary" href="{{route('admin.external.create')}}">+ Nový externí zdroj</a>
        <div class="row">
            <div class="col-xs-12 col-md-8">
                @component('admin.components.table', [
                    'id' => 'index_table',
                    'columns' => ['Odkaz', 'Písnička', 'Autor', 'Typ', 'Akce']
                ])
                    @foreach($externals as $external)
                    <tr>
                        <td>
                            <a href="{{route('admin.external.edit', ['external'=>$external->id])}}">{{$external->getPublicName()}}</a>
                        </td>
                        <td>
                            @if ($external->song_lyric != null)
                                <a href="{{ route('admin.song.edit', $external->song_lyric) }}">{{$external->song_lyric->name}}</a>
                            @else
                                (nepřiřazeno)
                            @endif
                        </td>
                        <td>
                            @if ($external->author != null)
                                <a href="{{ route('admin.author.edit', $external->author) }}">{{$external->author->name}}</a>
                            @else
                                (bez autora)
                            @endif
                        </td>
                        <td>{{ $external->getTypeString() }}</td>
                        <td>
                            @include('admin.components.deletebutton', [
                                'url' => route('admin.external.destroy', $external ),
                            ])
                        </td>
                    </tr>
                @endforeach
                @endcomponent
            </div>
        </div>
    </div>
@endsection

