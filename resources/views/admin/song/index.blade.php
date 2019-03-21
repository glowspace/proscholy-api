@extends('admin.layout')

@section('content')
    <div class="content-padding">
            <h2>{{ $title ?? "Seznam písní"}}</h2>
        <a class="btn btn-outline-primary" href="{{route('admin.song.create')}}">+ Nová píseň</a>
        <div class="row">
            <div class="col-xs-12 col-md-8">
                @component('admin.components.table', [
                    'id' => 'index_table',
                    'columns' => ['Název', 'Typ', 'Naposledy upraveno', 'Akce']
                ])
                    @foreach($song_lyrics as $song_l)
                    <tr>
                        <td><a href="{{route('admin.song.edit',['id'=>$song_l->id])}}">{{$song_l->name}}</a></td>
                            <td>
                                @if($song_l->is_original)
                                    originál
                                @else
                                    překlad
                                @endif
                                @if ($song_l->lang !== 'cs')
                                    &nbsp;({{ $song_l->getLanguageName() }})
                                @endif
                                @if ($song_l->is_published == false)
                                    - čeká na schválení editorem
                                @endif
                            </td>
                            <td>
                                {{ $song_l->updated_at }}
                            </td>
                            <td>
                            @include('admin.components.deletebutton', [
                                'url' => route('admin.song.destroy',[ 'song' => $song_l->id ]) ,
                            ])
                        </td>
                    </tr>
                    @endforeach
                @endcomponent
            </div>
        </div>
    </div>
@endsection

