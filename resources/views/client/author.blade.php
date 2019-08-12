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
                <div class="card-header p-1"><div class="px-3 py-2 d-inline-block">O @if($author->type == 0)
                        autorovi
                    @elseif($author->type == 1)
                        uskupení
                    @elseif($author->type == 2)
                        schole
                    @elseif($author->type == 3)
                        kapele
                    @elseif($author->type == 4)
                        sboru
                    @endif</div></div>
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
                <div class="card-header p-1"><div class="px-3 py-2 d-inline-block">Autorské písně</div></div>
                <div class="card-body p-0">
                    <table class="table m-0">
                        @foreach($originals as $key => $song_l)
                        <tr>
                            <td class="p-1 align-middle {{ $key ? '' : 'border-top-0' }}">
                                <a class="px-3 py-2 w-100 d-inline-block" href="{{ $song_l->public_url }}">{{$song_l->name}}</a>
                            </td>
                            <td class="px-1 py-2 align-middle {{ $key ? '' : 'border-top-0' }}">
                                @component('client.components.song_lyric_author', ['song_l' => $song_l])@endcomponent
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                <div class="card-footer p-1">
                    <div class="px-3 py-2 d-inline-block">
                        Zpěvník ProScholy.cz <img src="{{asset('img/logo_v2.png')}}" width="20"> {{date('Y')}}
                    </div>
                </div>
            </div>
        @endif

        @if($translations->count() > 0)
            <div class="card">
                <div class="card-header p-1"><div class="px-3 py-2 d-inline-block">Překlady</div></div>
                <div class="card-body p-0">
                    <table class="table m-0">
                        @foreach($translations as $key => $song_l)
                        <tr>
                            <td class="p-1 align-middle {{ $key ? '' : 'border-top-0' }}">
                                <a class="px-3 py-2 w-100 d-inline-block" href="{{ $song_l->public_url }}">{{$song_l->name}}</a>
                            </td>
                            <td class="px-1 py-2 align-middle {{ $key ? '' : 'border-top-0' }}">
                                @component('client.components.song_lyric_author', ['song_l' => $song_l])@endcomponent
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                <div class="card-footer p-1">
                    <div class="px-3 py-2 d-inline-block">
                        Zpěvník ProScholy.cz <img src="{{asset('img/logo_v2.png')}}" width="20"> {{date('Y')}}
                    </div>
                </div>
            </div>
        @endif

        @if($interpreted->count() > 0)
            <div class="card">
                <div class="card-header p-1"><div class="px-3 py-2 d-inline-block">Interpretace písní</div></div>
                <div class="card-body p-0">
                    <table class="table m-0">
                        @foreach($interpreted as $key => $song_l)
                        <tr>
                            <td class="p-1 align-middle {{ $key ? '' : 'border-top-0' }}">
                                <a class="px-3 py-2 w-100 d-inline-block" href="{{ $song_l->public_url }}">{{$song_l->name}}</a>
                            </td>
                            <td class="px-1 py-2 align-middle {{ $key ? '' : 'border-top-0' }}">
                                @component('client.components.song_lyric_author', ['song_l' => $song_l])@endcomponent
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                <div class="card-footer p-1">
                    <div class="px-3 py-2 d-inline-block">
                        Zpěvník ProScholy.cz <img src="{{asset('img/logo_v2.png')}}" width="20"> {{date('Y')}}
                    </div>
                </div>
            </div>
        @endif
    </div>


@endsection