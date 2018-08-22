@extends('layout')

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
                    @if(isset($record->songTranslation))
                        {!! $record->songTranslation->getLink() !!}
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

