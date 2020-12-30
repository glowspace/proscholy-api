@extends('layout.admin')

@php ($loading_text = 'Načítání…')

@section('content-withmenu')
    <div class="__container-fluid dashboard-container">
        <h1>Nástěnka administrace</h1>
        <p>Vítej v administraci hudební databáze ProScholy.</p>
        <div class="row">
            <div class="col-md-8">
                <div class="content-label">Statistika</div>
                <div class="card">
                    <div class="card-body p-0">
                        <table class="table  mb-0 statistics-table">
                            <tr is="progress-row"
                                title="Všechny písně"
                                link="songs"
                                number="{{number_format($songs_count, 0, ',', ' ')}}"
                            ><td>{{$loading_text}}</td></tr>
                            <tr is="progress-row"
                                title="Písně s&nbsp;textem"
                                link="songs#no-lyrics"
                                number="{{number_format($songs_w_text_count, 0, ',', ' ')}}"
                                percent="{{round(($songs_w_text_count/$songs_count)*100)}}"
                            ><td>{{$loading_text}}</td></tr>
                            <tr is="progress-row"
                                title="Písně s&nbsp;akordy"
                                link="songs#no-chords"
                                number="{{number_format($songs_w_chords_count, 0, ',', ' ')}}"
                                percent="{{round(($songs_w_chords_count/$songs_count)*100)}}"
                            ><td>{{$loading_text}}</td></tr>
                            <tr is="progress-row"
                                title="Písně s&nbsp;notami"
                                number="{{number_format($songs_w_score_count, 0, ',', ' ')}}"
                                percent="{{round(($songs_w_score_count/$songs_count)*100)}}"
                            ><td>{{$loading_text}}</td></tr>
                            <tr is="progress-row"
                                title="LilyPond noty"
                                number="{{number_format($songs_w_lilypond_count, 0, ',', ' ')}}"
                                percent="{{round(($songs_w_lilypond_count/$songs_count)*100)}}"
                                link="songs#needs-lilypond"
                            ><td>{{$loading_text}}</td></tr>
                            <tr is="progress-row"
                                title="Písně s&nbsp;licencí"
                                number="{{number_format($songs_w_license_count, 0, ',', ' ')}}"
                                percent="{{round(($songs_w_license_count/$songs_count)*100)}}"
                            ><td>{{$loading_text}}</td></tr>
                            <tr is="progress-row"
                                title="Písně se štítky"
                                link="songs#no-tags"
                                number="{{number_format($songs_w_tags_count, 0, ',', ' ')}}"
                                percent="{{round(($songs_w_tags_count/$songs_count)*100)}}"
                            ><td>{{$loading_text}}</td></tr>
                            {{-- <tr is="progress-row"
                                title="Kompletní písně"
                                number="{{number_format($songs_w_all_count, 0, ',', ' ')}}"
                                percent="{{round(($songs_w_all_count/$songs_count)*100)}}"
                            ><td>{{$loading_text}}</td></tr> --}}
                            <tr is="progress-row"
                                title="Autoři"
                                link="author"
                                number="{{number_format($authors_count, 0, ',', ' ')}}"
                            ><td>{{$loading_text}}</td></tr>
                            <tr is="progress-row"
                                title="Materiály"
                                link="external"
                                number="{{number_format($externals_count, 0, ',', ' ')}}"
                            ><td>{{$loading_text}}</td></tr>
                        </table>
                    </div>
                </div>
                <div class="content-label">Důležité odkazy</div>
                <div class="dash">
                    <div class="row">
                        <div class="col-sm-4">
                            <a href="https://slack.com/app_redirect?team=TCC9MSFQA&channel=CCC2UEP1A"
                               target="_blank">
                                <div class="card">
                                    <img src="{{asset('img/icons/slack.svg')}}"
                                         class="card-img-top"/>
                                    <div class="card-body">
                                        <h5 class="card-title">Slack</h5>
                                        <p class="card-text text-muted">týmová komunikace</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-4">
                            <a href="https://slack.com/app_redirect?team=TCC9MSFQA&channel=CGHL024DD"
                               target="_blank">
                                <div class="card">
                                    <span class="img-icon"><i class="fas fa-question-circle"></i></span>
                                    <div class="card-body">
                                        <h5 class="card-title">Technická podpora</h5>
                                        <p class="card-text text-muted">kanál na Slacku</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-4">
                            <a href="tel:+420734791909">
                                <div class="card">
                                    <span class="img-icon"><i class="fas fa-phone-square"></i></span>
                                    <div class="card-body">
                                        <h5 class="card-title">Krizový telefon</h5>
                                        <h5 class="card-title"><b>734 791 909</b></h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <a href="https://docs.google.com/spreadsheets/d/1iE38u0TeK9nWgYKUZQt4YxnRPP-nL4ogLiplh4TJgk4/edit?usp=sharing"
                               target="_blank">
                                <div class="card">
                                    <img src="{{asset('img/icons/sheets.png')}}"
                                         class="card-img-top"/>
                                    <div class="card-body">
                                        <h5 class="card-title">Data, kontakty</h5>
                                        <p class="card-text text-muted">seznam zpěvníků, autorů</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-4">
                            <a href="https://drive.google.com/drive/folders/1wTz9aCgkwLizbyOGmHhszoQ7yLPGmSet"
                               target="_blank">
                                <div class="card">
                                    <img src="{{asset('img/icons/drive.png')}}"
                                         class="card-img-top"/>
                                    <div class="card-body">
                                        <h5 class="card-title">Redakce</h5>
                                        <p class="card-text text-muted">sdílená složka</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-4">
                            <a href="https://docs.google.com/document/d/1leyWsVScenFDaYrzhWU487o_TMoCF-42QXvPPVaRo-M/edit?usp=sharing"
                               target="_blank">
                                <div class="card">
                                    <img src="{{asset('img/icons/docs.png')}}"
                                         class="card-img-top"/>
                                    <div class="card-body">
                                        <h5 class="card-title">Informace o&nbsp;projektu</h5>
                                        <p class="card-text text-muted">ProScholy.cz</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <a href="https://www.youtube.com/watch?list=PLXLfC_XTiu7qWXgsf-18mPu-IWFZ5o2xn&v=yZC-_uYhdvI"
                               target="_blank">
                                <div class="card">
                                    <img src="{{asset('img/icons/youtube.png')}}"
                                         class="card-img-top"/>
                                    <div class="card-body">
                                        <h5 class="card-title">Instruktážní videa</h5>
                                        <p class="card-text text-muted">od Janey</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-4">
                            <a href="https://discord.gg/KDK64kr"
                               target="_blank">
                                <div class="card">
                                    <img src="{{asset('img/icons/discord.svg')}}"
                                         class="card-img-top"/>
                                    <div class="card-body">
                                        <h5 class="card-title">Discord server</h5>
                                        <p class="card-text text-muted">místo pravidelných virtuálních setkání</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-4">
                            <a href="https://trello.com/b/IzNkczwd/redakce-proscholycz"
                               target="_blank">
                                <div class="card">
                                    <img src="{{asset('img/icons/trello.svg')}}"
                                         class="card-img-top"/>
                                    <div class="card-body">
                                        <h5 class="card-title">Trello</h5>
                                        <p class="card-text text-muted">nástroj k rozdělování úkolů</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="{{asset('img/icons/profile.jpg')}}"
                            style="height:150px"
                            class="rounded-circle mt-2"
                            alt="avatar"
                        />
                        <br>
                        <h3 class="mb-2">{{ Auth::user()->name }}</h3>
                        <h4 class="mx-2 mt-0 text-secondary">{{ Auth::user()->roles->count() > 0 ? Auth::user()->roles->first()->name : '' }}</h4>
                        <div class="card mb-0">
                            <div class="card-body mb-0">
                                <span>Díky za dobře odvedenou práci!</span>
                                <br>Tvé příspěvky si zobrazilo
                                <b><user-stats
                                    user-id="{{ Auth::user()->id }}"
                                    :embedded="true"
                                ></user-stats>&nbsp;lidí</b>.
                            </div>
                        </div>
                    </div>
                </div>
                <user-stats
                    user-id="{{ Auth::user()->id }}"
                    :embedded="false"
                ></user-stats>
            </div>
        </div>
    </div>
@endsection
