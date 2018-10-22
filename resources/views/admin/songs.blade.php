@extends('layout')

@section('content')
    <table class="table table-bordered" style="width:600px; margin: 0 auto;">
        @foreach($songs as $song)
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

@endsection
