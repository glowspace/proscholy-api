@extends('admin.layout')

@section('content')
    <div class="content-padding">
        <h2>Seznam písní (seřazeno od nejnověji přidaných)</h2>
        <a class="btn btn-outline-primary" href="{{route('admin.song.create')}}">+ Nová píseň</a>
        <div class="row">
            <div class="col-sm-6">
                <table class="table table-bordered">
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
                            @include('admin.components.deletebutton', [
                                'url' => route('admin.song.delete',[ 'song' => $song_l->id ]) ,
                                'class' => 'btn btn-warning'
                            ])
                            </td>
                        </tr>
                    @endforeach
                </table>

                {!! $song_lyrics->links() !!}
            </div>
        </div>
    </div>
@endsection

@include('admin.components.deletebutton_includes')