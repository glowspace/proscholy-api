@extends('layout.master')

@section('navbar')
    @include('client.components.menu_song')
@endsection

@section('content')
    <div class="content-padding">
        <h1>Hudební podklady písně {{$song_l->name}}</h1>

        @if($song_l->audioTracks()->count() > 0)
            <div class="card">
                <div class="card-header">V online streamovacích službách</div>
                <div class="card-body">
                    <div class="row">
                        @foreach($song_l->audioTracks() as $external)
                            <div class="col-sm-4">
                                @component('client.components.external_embed', ['external'=> $external])@endcomponent
                            </div>
                        @endforeach
                    </div>

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

    @include('scripts.chordpro_parse')

    <script>
        $(document).ready(function () {
            let lyrics = document.getElementById('lyrics');
            let lyrics_source = document.getElementById('lyrics').innerHTML;

            lyrics.innerHTML = parseChordPro(lyrics_source, 0);
        });
    </script>
@endpush
