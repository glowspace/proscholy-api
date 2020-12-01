@extends('layout.admin')

@section('content-withmenu')
    <div class="__container-fluid dashboard-container">

        <div class="content-header">
            <h1>Nástěnka administrace</h1>
        </div>

        <div class="row align-items-start">
            <div class="col-md-8 d-flex flex-wrap p-0">
                <div class="dash d-flex flex-wrap">
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
                    <a href="tel:+420734791909">
                        <div class="card">
                            <span class="img-icon"><i class="fas fa-phone-square"></i></span>
                            <div class="card-body">
                                <h5 class="card-title">Krizový telefon</h5>
                                <h5 class="card-title"><b>734 791 909</b></h5>
                            </div>
                        </div>
                    </a>
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
                    <a href="https://docs.google.com/document/d/1leyWsVScenFDaYrzhWU487o_TMoCF-42QXvPPVaRo-M/edit?usp=sharing"
                       target="_blank">
                        <div class="card">
                            <img src="{{asset('img/icons/docs.png')}}"
                                 class="card-img-top"/>
                            <div class="card-body">
                                <h5 class="card-title">Regenschori</h5>
                                <p class="card-text text-muted">informace o projektu</p>
                            </div>
                        </div>
                    </a>
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
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body mb-0 h4">
                        <i class="fas fa-heart pr-2"></i>
                        <span>Díky, že pomáháš!</span>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body p-0">
                        <table class="table table-bordered mb-0 statistics-table">
                            <tr>
                                <td colspan="3"><b>Počty písní, autorů a odkazů ve Zpěvníku</b></td>
                            </tr>
                            <tr>
                                <td>Písně s&nbsp;textem</td>
                                <td>{{round(($songs_w_text_count/$songs_count)*100)}}&nbsp;%</td>
                                <td><b>{{number_format($songs_w_text_count, 0, ',', ' ')}}</b></td>
                            </tr>
                            <tr>
                                <td>Písně s&nbsp;textem, autorem, akordy i&nbsp;štítky</td>
                                <td>{{round(($songs_w_all_count/$songs_count)*100)}}&nbsp;%</td>
                                <td><b class="text-success">{{number_format($songs_w_all_count, 0, ',', ' ')}}</b></td>
                            </tr>
                            <tr>
                                <td>Písně pouze s&nbsp;názvem</td>
                                <td>{{round(($songs_w_just_title_count/$songs_count)*100)}}&nbsp;%</td>
                                <td>
                                    <b class="text-warning">{{number_format($songs_w_just_title_count, 0, ',', ' ')}}</b>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><a href="{{route('admin.song.index')}}">Písně celkem</a></td>
                                <td><b class="text-primary">{{number_format($songs_count, 0, ',', ' ')}}</b></td>
                            </tr>
                            <tr>
                                <td colspan="2"><a href="{{route('admin.author.index')}}">Autoři</a></td>
                                <td><b>{{number_format($authors_count, 0, ',', ' ')}}</b></td>
                            </tr>
                            <tr>
                                <td colspan="2"><a href="{{route('admin.external.index')}}">Materiály</a></td>
                                <td><b>{{number_format($externals_count, 0, ',', ' ')}}</b></td>
                            </tr>
                        </table>
                    </div>
                </div>
                @if (Auth::check())
                    <div class="card">
                        <div class="card-body mb-0 h4">
                            <i class="fas fa-user pr-2"></i>
                            <span>{{ Auth::user()->name }}</span>
                            @if (Auth::user()->roles()->count() > 0)
                                <span>({{Auth::user()->roles()->first()->name}})</span>
                            @endif
                            <span class="mx-2 text-secondary"><user-stats user-id="{{ Auth::user()->id }}"
                                                                          :embedded="true"></user-stats></span>
                        </div>
                    </div>
                    <user-stats user-id="{{ Auth::user()->id }}"
                                :embedded="false"></user-stats>
                @endif
            </div>
        </div>
    </div>
@endsection
