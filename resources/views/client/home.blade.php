@extends('layout.client')

@section('navbar')
    <nav class="navbar navbar-expand-lg navbar-dark justify-content-between absolute-top">
        <div class="container">
        <a class="navbar-brand" href="#"><img src="{{asset('img/logo_v2.png')}}" style="padding: 0 10px 0 0;" width="60">
            Zpěvník pro scholy
            <span style="color: #ffffff3d">- na pomoc všem, kteří se chtějí modlit hudbou</span>
        </a>
            {{-- <div>
                <a href="#" class="btn btn-secondary"><i class="fas fa-search"></i> Vyhledávání</a>
                <a href="#" class="btn btn-secondary"><i class="fas fa-book"></i> Zpěvníky</a>
                <a href="#" class="btn btn-secondary"><i class="fas fa-user"></i> Autoři písní</a>
                <a href="#" class="btn btn-secondary"><i class="fas fa-info"></i> O zpěvníku</a>
                <a href="#" class="btn btn-secondary"><i class="fas fa-plus"></i> Přidat píseň</a>
                <a href="#" class="btn btn-secondary"><i class="fas fa-moon"></i> Tmavý mód</a>
            </div> --}}
        </div>
    </nav>
    
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a href="#" class="btn"><img src="{{asset('img/logo_v2.png')}}" height="20"></a>
            <a href="#" class="btn btn-secondary"><i class="fas fa-search"></i></a>
            <a href="#" class="btn btn-secondary"><i class="fas fa-book"></i></a>
            <a href="#" class="btn btn-secondary"><i class="fas fa-user"></i></a>
            <a href="#" class="btn btn-secondary"><i class="fas fa-info"></i></a>
            <a href="#" class="btn btn-secondary"><i class="fas fa-plus"></i></a>
            <a href="#" class="btn btn-secondary"><i class="fas fa-moon"></i></a>
        </div>
    </nav>
@endsection

{{-- @section('navbar')
    @include('client.components.menu_main')

    <div class="alert alert-primary"
         role="alert"
         style="margin: 12px; border-radius: 5px">
        <p>Vítejte v digitálním zpěvníku <b>ProScholy.cz</b>, který přichází na pomoc všem scholám, křesťanským kapelám,
            společenstvím a
            všem, kdo se chtějí modlit hudbou!</p>
        <hr>
        <p class="mb-0">Naše redakce teď pro vás připravuje první písně. Aktuálně je přidáno <b>{{$song_count}}</b>
            písní.</p>
    </div>

    @auth
        <a class="btn btn-secondary" href="{{route('admin.dashboard')}}">
            <i class="fas fa-users"></i> Administrace
        </a>
    @endauth --}}



    {{--<div style="margin-top: 20px">--}}
    {{--<div class="navbar-label material-shadow text-warning">Nejnavštěvovanější písně</div>--}}
    {{--@foreach($top_songs as $song_l)--}}
    {{--<a class="btn btn-secondary" href="{{route('client.song.text', $song_l)}}">--}}
    {{--<i class="fas fa-music"></i>{{$song_l->name}}</a>--}}
    {{--@endforeach--}}
    {{--</div>--}}
{{-- @endsection --}}

@section('content')
    <div class="background-home">
        <div class="container-fluid">
            <div class="logo-wrapper ">
                <div class="logo"></div>
                <span class="caption noselect">Zpěvník</span>
            </div>
            <div class="search-wrapper">
                <form method="POST"
                      action="{{route('client.search')}}">
                    @csrf
                    <input class="search-home"
                           name="query"
                           placeholder="Zadejte název písně (třeba Ať požehnán je Bůh)"
                           :autofocus="'autofocus'"
                           type="search">
                    <button type="submit"
                            class="search-submit">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="home-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">

                    <h1>Redakce ProScholy.cz</h1>
            
                    <p>Jsme skupina lidí, kteří chtějí usnadnit práci všem,
                        kteří pracují s českou nebo slovenskou křesťanskou tvorbou.
                        V dnešní době, kdy jsou materiály i potřebné know-how roztroušeny po mnoha různých místech
                        na internetu nebo v papírových sbornících a zpěvnících,
                        chceme nabídnout rychlou a praktickou alternativu.
                    </p>
            
                    <p>
                        Chcete se podílet na tvorbě zpěvníku?
                        Máte tipy na nové funkce nebo nový repertoár?
                        Můžete nám napsat na email <a href="mailto:redakce@proscholy.cz">redakce@proscholy.cz</a> nebo se na nás obrátit telefonicky na <b>734 791 909</b>.</p>
            
                    <h3>Náš tým</h3>
            
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Michael Dojčár</h5>
                                    <p class="card-text">celková koordinace projektu, IT</p>
                                </div>
                            </div>
                        </div>
            
                        <div class="col-sm-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Miroslav Šerý</h5>
                                    <p class="card-text">vedoucí vývojář</p>
                                </div>
                            </div>
                        </div>
            
                        <div class="col-sm-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Jana Stuchlíková</h5>
                                    <p class="card-text">redakční tým</p>
                                </div>
                            </div>
                        </div>
            
                        <div class="col-sm-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Mgr. Ondřej Talaš</h5>
                                    <p class="card-text">koordinace, duchovní podpora</p>
                                </div>
                            </div>
                        </div>
            
                        <div class="col-sm-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Mag. art. Ondřej Múčka</h5>
                                    <p class="card-text">redakční tým</p>
                                </div>
                            </div>
                        </div>
            
                        <div class="col-sm-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Matěj Kulišťák</h5>
                                    <p class="card-text">redakční tým</p>
                                </div>
                            </div>
                        </div>
            
                        <div class="col-sm-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Barbora Kuchaříková</h5>
                                    <p class="card-text">redakční tým</p>
                                </div>
                            </div>
                        </div>
            
                        <div class="col-sm-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Terezie Kološová</h5>
                                    <p class="card-text">redakční tým</p>
                                </div>
                            </div>
                        </div>
            
                        <div class="col-sm-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Zuzana Haikerová</h5>
                                    <p class="card-text">redakční tým</p>
                                </div>
                            </div>
                        </div>
            
                        <div class="col-sm-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Václav Šablatura</h5>
                                    <p class="card-text">redakční tým, odborný konzultant</p>
                                </div>
                            </div>
                        </div>
            
                        <div class="col-sm-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Benjamín Tichý</h5>
                                    <p class="card-text">grafika</p>
                                </div>
                            </div>
                        </div>
            
                        <div class="col-sm-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Petra Kalousková</h5>
                                    <p class="card-text">PR tým</p>
                                </div>
                            </div>
                        </div>
            
                        <div class="col-sm-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Emma Kasanová</h5>
                                    <p class="card-text">PR tým</p>
                                </div>
                            </div>
                        </div>
            
                        <div class="col-sm-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Josef Řídký</h5>
                                    <p class="card-text">mobilní aplikace</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Vít Kološ</h5>
                                    <p class="card-text">vývoj a návrh web. rozhraní</p>
                                </div>
                            </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
