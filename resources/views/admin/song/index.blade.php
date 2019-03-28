@extends('admin.layout')

@section('content')
    <div class="content-padding">
        <h2>{{ $title ?? "Seznam písní"}}</h2>
        <a class="btn btn-outline-primary" href="{{route('admin.song.create')}}">+ Nová píseň</a>
        {{-- todo add filter by author button for admins --}}
        {{-- @hasrole('admin')
            <my-checkbox inline-template>
                <div class="checkbox-wrapper" @click="check">
                <div :class="{ checkbox: true, checked: checked }"></div>
                <div class="title"></div>
                </div>  
            </my-checkbox>
        @endhasrole --}}
        <div class="row">
            <div class="col-xs-12 col-md-8">
                @component('admin.components.table', [
                    'id' => 'index_table',
                    'columns' => ['Název', 'Typ', 'Naposledy upraveno', 'Publikováno', 'Schváleno autorem', 'Akce']
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
                            </td>
                            <td>
                                {{ $song_l->updated_at }}
                            </td>
                            <td>
                                @if ($song_l->is_published)Ano @else Ne @endif
                            </td>
                            <td>
                                @if ($song_l->is_approved_by_author)Ano @else Ne @endif
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

