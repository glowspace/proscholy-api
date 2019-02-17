@extends('layout.layout')

@section('navbar')
    @include('client.components.menu_song')
@endsection

@section('content')
    <div class="content-padding">
        <h1>{{$song_l->name}}</h1>

        <div class="card">
            <div class="card-header">Píseň <b>{{$song_l->name}}</b> na YouTube</div>
            <div class="card-body">
                <div class="row">
                    @if($song_l->youtubeVideos->count() == 0)
                        <div class="col-sm-4">
                            <div class="card embed-responsive embed-responsive-16by9"></div>
                        </div>
                        <div class="col-sm-4">
                            <div class="card embed-responsive embed-responsive-16by9"></div>
                        </div>
                        <div class="col-sm-4">
                            <div class="card embed-responsive embed-responsive-16by9"></div>
                        </div>
                    @elseif($song_l->youtubeVideos->count() == 1)
                        @foreach($song_l->youtubeVideos as $external)
                            <div class="col-sm-4">
                                @component('client.components.external_embed', ['external'=> $external])@endcomponent
                            </div>
                        @endforeach
                        <div class="col-sm-4">
                            <div class="card embed-responsive embed-responsive-16by9"></div>
                        </div>
                        <div class="col-sm-4">
                            <div class="card embed-responsive embed-responsive-16by9"></div>
                        </div>
                    @elseif($song_l->youtubeVideos->count() == 2)
                        @foreach($song_l->youtubeVideos as $external)
                            <div class="col-sm-4">
                                @component('client.components.external_embed', ['external'=> $external])@endcomponent
                            </div>
                        @endforeach
                        <div class="col-sm-4">
                            <div class="card embed-responsive embed-responsive-16by9"></div>
                        </div>
                    @elseif($song_l->youtubeVideos->count() > 2)
                        @foreach($song_l->youtubeVideos as $external)
                            <div class="col-sm-4">
                                @component('client.components.external_embed', ['external'=> $external])@endcomponent
                            </div>
                        @endforeach
                    @endif



                </div>

                <hr>
                Zpěvník ProScholy.cz <img src="{{asset('img/logo_v2.png')}}" width="20"> {{date('Y')}}
            </div>
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
