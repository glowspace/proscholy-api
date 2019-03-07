@extends('layout.master')

@section('title', $song_l->name . ' - noty')

@section('navbar')
    @include('client.components.menu_song')
@endsection

@section('content')
    <div class="content-padding">
        <h1>Všechny verze písně {{$song_l->name}}</h1>

        @if($song_l_original !== null)
            <div class="card">
                <div class="card-header">Původní originál @if ($song_l->lang !== "cs")
                    ({{ $song_l->getLanguageName() }})
                @endif</div>
                <div class="card-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>
                                    <a href="{{route('client.song.text', $song_l_original)}}">{{$song_l_original->name}}</a>
                                </td>
                                <td>
                                    @component('client.components.song_lyric_author', ['song_l' => $song_l_original])@endcomponent
                                </td>
                                <td>{{$song_l_original->visits}} x</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @if($song_l->song->song_lyrics()->count() > 0)
            <div class="card">
                @if ($song_l_original !== null)
                    <div class="card-header">Překlady</div>
                @endif
                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Název překladu</th>
                            <th scope="col">Autor</th>
                            <th scope="col">Zobrazeno</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($song_l->song->translations()->get() as $song_l)
                            <tr>
                                <td>
                                    <a href="{{route('client.song.text', $song_l)}}">{{$song_l->name}}</a>
                                </td>
                                <td>
                                    @component('client.components.song_lyric_author', ['song_l' => $song_l])@endcomponent
                                </td>
                                <td>{{$song_l->visits}} x</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                    <hr>
                    Zpěvník ProScholy.cz <img src="{{asset('img/logo_v2.png')}}" width="20"> {{date('Y')}}
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script
            src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>
@endpush
