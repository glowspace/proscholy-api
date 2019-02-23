@extends('admin.layout')

@section('content')
    <div class="content-padding">
        <h2>Seznam písní</h2>
        <a class="btn btn-outline-primary" href="{{route('admin.song.create')}}">+ Nová píseň</a>
        <div class="row">
            <div class="col-xs-12 col-md-8">
                <table class="table table-bordered" id="index_table">
                    <thead> 
                        <tr>
                            <th>Název</th>
                            <th>Typ</th>
                            <th>Naposledy upraveno</th>
                            <th>Akce</th>
                        </tr>
                    </thead>

                    <tbody>
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
                                @include('admin.components.deletebutton', [
                                    'url' => route('admin.song.delete',[ 'song' => $song_l->id ]) ,
                                    'class' => 'btn btn-warning'
                                ])
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@include('admin.components.deletebutton_includes')

@include('admin.components.datatable_includes')
@include('admin.components.datatable', ['table_id' => 'index_table'])