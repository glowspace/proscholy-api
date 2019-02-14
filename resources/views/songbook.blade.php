@extends('layout.layout_old')

@section('content')
    <h2>Zpěvník {{$songbook->name}}</h2>

    <table class="table table-bordered">
        <th>Číslo</th>
        <th>Název</th>
        @foreach($records as $record)
            <tr>
                <td>
                    <a>{{$record->number}}</a>
                </td>
                <td>
                    @if(isset($record->songLyric))
                        {!! $record->songLyric->getLink() !!}
                    @elseif(isset($record->placeholder))
                       {{$record->placeholder}}
                    @else
                        <i>neznámá píseň</i>
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
@endsection

