@extends('layout.layout')

@section('content')
    <div class="content-padding">
        <table class="table table-bordered" style="width:600px; margin: 0 auto;">
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
                </tr>
            @endforeach
        </table>
    </div>
@endsection
