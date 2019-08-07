@extends('layout.client')

@section('content')
    <div class="container">
        <h2 style="margin-bottom: 0">{{$author->name}}</h2>
        @if($author->type == 0)
            <span style="color: dimgrey">autor</span>
        @elseif($author->type == 1)
            <span style="color: dimgrey">hudební uskupení</span>
        @elseif($author->type == 2)
            <span style="color: dimgrey">schola</span>
        @elseif($author->type == 3)
            <span style="color: dimgrey">kapela</span>
        @elseif($author->type == 4)
            <span style="color: dimgrey">sbor</span>
        @endif
        <br><br>

        @if(isset($author->description))
            <div class="card">
                <div class="card-header">O @if($author->type == 0)
                        autorovi
                    @elseif($author->type == 1)
                        uskupení
                    @elseif($author->type == 2)
                        schole
                    @elseif($author->type == 3)
                        kapele
                    @elseif($author->type == 4)
                        sboru
                    @endif</div>
                <div class="card-body">{{$author->description}}</div>
            </div>
        @endif

        {{-- @if($author->members()->count() > 0)
            Členové:<br>
            @foreach($author->members as $member)
                <a href="{{route('author.single', ['id'=> $member->id])}}">{{$member->name}}</a><br>
            @endforeach
        @endif

        @if($author->memberships->count() > 0)
            Skupiny:<br>

            @foreach($author->isMemberOf as $author)
                <a href="{{route('author.single', ['id'=> $author->id])}}">{{$author->name}}</a><br>
            @endforeach
            <br>
        @endif --}}

        @if($originals->count() > 0)
            <div class="card">
                <div class="card-header">Autorské písně</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Píseň</th>
                            <th scope="col">Autor</th>
                            <th scope="col">Zobrazeno</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($originals as $song_l)
                            <tr>
                                <td>
                                    <a href="{{ $song_l->public_url }}">{{$song_l->name}}</a>
                                </td>
                                <td>
                                    @component('client.components.song_lyric_author', ['song_l' => $song_l])@endcomponent
                                </td>
                                <td>{{$song_l->visits}} x</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                    <div class="row">

                    </div>

                    <hr>
                    Zpěvník ProScholy.cz <img src="{{asset('img/logo_v2.png')}}" width="20"> {{date('Y')}}
                </div>
            </div>
        @endif

        @if($translations->count() > 0)
            <div class="card">
                <div class="card-header">Překlady</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Píseň</th>
                            <th scope="col">Autor</th>
                            <th scope="col">Zobrazeno</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($translations as $song_l)
                            <tr>
                                <td>
                                    <a href="{{ $song_l->public_url }}">{{$song_l->name}}</a>
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

        @if($interpreted->count() > 0)
            <div class="card">
                <div class="card-header">Interpretace písní</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Píseň</th>
                            <th scope="col">Autor</th>
                            <th scope="col">Zobrazeno</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($interpreted as $song_l)
                            <tr>
                                <td>
                                    <a href="{{ $song_l->public_url }}">{{$song_l->name}}</a>
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