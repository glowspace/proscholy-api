@extends('layout.layout')

@section('navbar')

@endsection

@section('content')
    <div class="content-padding">
        <h1>Výsledky vyhledávání pro {{$phrase}}</h1>

        <table class="table">
            {{-- implements interface ISearchResult --}}
            @foreach($search_results as $result)
                <tr>
                    <td>{{$result->getSearchTitle()}}</td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
