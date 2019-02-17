@extends('admin.layout')

@section('content')
    <div class="content-padding">
        <h2>Seznam písní (seřazeno od nejnověji přidaných)</h2>
        <a class="btn btn-outline-primary" href="{{route('admin.song.create')}}">+ Nová píseň</a>
        <div class="row">
            <div class="col-sm-6">
                <table class="table table-bordered">
                    @foreach($song_lyrics as $song)
                    <tr>
                        <td><a href="{{route('admin.song.edit',['id'=>$song->id])}}">{{$song->name}}</a></td>
                            <td>
                                @if($song->is_original)
                                    originál
                                @else
                                    překlad
                                @endif
                            </td>
                            <td>
                            {{-- <a href="{{ route('admin.song.delete',['id'=>$song->id]) }}" class="btn btn-warning">Vymazat</a> --}}
                            @include('admin.components.deletebutton', ['url' => route('admin.song.delete',['song' => $song->id] )])
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