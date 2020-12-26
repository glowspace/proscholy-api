@extends('layout.admin')

@section('content-withmenu')
    <div class="__container-fluid dashboard-container">

        <div class="content-header">
            <h1>Nástěnka administrace</h1>
        </div>

        <p>Vítej v administraci hudební databáze Regenschori.</p>


        <div class="row">
            <div class="col-md-8">
                <div class="content-label">Statistika</div>

                <div class="card">
                    <div class="card-body p-0">
                        <table class="table  mb-0 statistics-table">
                            <tr>
                                <td>Písně s textem</td>
                                <td>{{round(($songs_w_text_count/$songs_count)*100)}}&nbsp;%</td>

                                <td style="width: 50%">
                                    <div class="progress rounded"
                                         style="height: 15px">
                                        <div class="progress-bar rounded bg-primary"
                                             role="progressbar"
                                             style="width: {{round(($songs_w_text_count/$songs_count)*100)}}%;"
                                             aria-valuenow="{{round(($songs_w_text_count/$songs_count)*100)}}"
                                             aria-valuemin="0"
                                             aria-valuemax="100"></div>
                                    </div>
                                </td>

                                <td><b>{{number_format($songs_w_text_count, 0, ',', ' ')}}
                                        / {{number_format($songs_count, 0, ',', ' ')}}</b></td>
                                <td>
                                    <a href="/admin/songs#no-lyrics">doplnit</a>
                                </td>

                            </tr>

                            <tr>
                                <td>Písně s akordy</td>
                                <td>{{round(($songs_w_chords_count/$songs_count)*100)}}&nbsp;%</td>

                                <td style="width: 50%">
                                    <div class="progress rounded"
                                         style="height: 15px">
                                        <div class="progress-bar rounded bg-primary"
                                             role="progressbar"
                                             style="width: {{round(($songs_w_chords_count/$songs_count)*100)}}%;"
                                             aria-valuenow="{{round(($songs_w_chords_count/$songs_count)*100)}}"
                                             aria-valuemin="0"
                                             aria-valuemax="100"></div>
                                    </div>
                                </td>



                                <td><b>{{number_format($songs_w_chords_count, 0, ',', ' ')}}
                                        / {{number_format($songs_count, 0, ',', ' ')}}</b></td>

                                <td>
                                    <a href="/admin/songs#no-chords">doplnit</a>
                                </td>

                            </tr>

                            <tr>
                                <td>Písně s notami</td>
                                <td>{{round(($songs_w_score_count/$songs_count)*100)}}&nbsp;%</td>

                                <td style="width: 50%">
                                    <div class="progress rounded"
                                         style="height: 15px">
                                        <div class="progress-bar rounded bg-primary"
                                             role="progressbar"
                                             style="width: {{round(($songs_w_score_count/$songs_count)*100)}}%;"
                                             aria-valuenow="{{round(($songs_w_score_count/$songs_count)*100)}}"
                                             aria-valuemin="0"
                                             aria-valuemax="100"></div>
                                    </div>
                                </td>



                                <td><b>{{number_format($songs_w_score_count, 0, ',', ' ')}}
                                        / {{number_format($songs_count, 0, ',', ' ')}}</b></td>

                                <td></td>

                            </tr>

                            <tr>
                                <td>LilyPond noty</td>
                                <td>{{round(($songs_w_lilypond_count/$songs_count)*100)}}&nbsp;%</td>

                                <td style="width: 50%">
                                    <div class="progress rounded"
                                         style="height: 15px">
                                        <div class="progress-bar rounded bg-primary"
                                             role="progressbar"
                                             style="width: {{round(($songs_w_lilypond_count/$songs_count)*100)}}%;"
                                             aria-valuenow="{{round(($songs_w_lilypond_count/$songs_count)*100)}}"
                                             aria-valuemin="0"
                                             aria-valuemax="100"></div>
                                    </div>
                                </td>



                                <td><b>{{number_format($songs_w_lilypond_count, 0, ',', ' ')}}
                                        / {{number_format($songs_count, 0, ',', ' ')}}</b></td>

                                <td></td>

                            </tr>

                            <tr>
                                <td>Písně s licencí</td>
                                <td>{{round(($songs_w_license_count/$songs_count)*100)}}&nbsp;%</td>

                                <td style="width: 50%">
                                    <div class="progress rounded"
                                         style="height: 15px">
                                        <div class="progress-bar rounded bg-primary"
                                             role="progressbar"
                                             style="width: {{round(($songs_w_license_count/$songs_count)*100)}}%;"
                                             aria-valuenow="{{round(($songs_w_license_count/$songs_count)*100)}}"
                                             aria-valuemin="0"
                                             aria-valuemax="100"></div>
                                    </div>
                                </td>



                                <td><b>{{number_format($songs_w_license_count, 0, ',', ' ')}}
                                        / {{number_format($songs_count, 0, ',', ' ')}}</b></td>

                                <td></td>

                            </tr>

                            <tr>
                                <td>Písně se štítky</td>

                                <td>{{round(($songs_w_tags_count/$songs_count)*100)}}&nbsp;%</td>

                                <td style="width: 50%">
                                    <div class="progress rounded"
                                         style="height: 15px">
                                        <div class="progress-bar rounded bg-primary"
                                             role="progressbar"
                                             style="width: {{round(($songs_w_tags_count/$songs_count)*100)}}%;"
                                             aria-valuenow="{{round(($songs_w_tags_count/$songs_count)*100)}}"
                                             aria-valuemin="0"
                                             aria-valuemax="100"></div>
                                    </div>
                                </td>

                                <td><b>{{number_format($songs_w_tags_count, 0, ',', ' ')}}
                                        / {{number_format($songs_count, 0, ',', ' ')}}</b></td>

                                <td><a href="/admin/songs#no-tags">doplnit</a></td>

                            </tr>

                            {{--                            <tr>--}}
                            {{--                                <td>Písně s notami</td>--}}
                            {{--                                <td style="width: 50%">--}}
                            {{--                                    <div class="progress rounded"--}}
                            {{--                                         style="height: 15px">--}}
                            {{--                                        <div class="progress-bar rounded bg-primary"--}}
                            {{--                                             role="progressbar"--}}
                            {{--                                             style="width: {{round(($songs_w_lilypond_count/$songs_w_license_count)*100)}}%;"--}}
                            {{--                                             aria-valuenow="{{round(($songs_w_lilypond_count/$songs_w_license_count)*100)}}"--}}
                            {{--                                             aria-valuemin="0"--}}
                            {{--                                             aria-valuemax="100"></div>--}}
                            {{--                                    </div>--}}
                            {{--                                </td>--}}

                            {{--                                <td>{{round(($songs_w_score_count/$songs_count)*100)}}&nbsp;%</td>--}}

                            {{--                                <td><b>{{number_format($songs_w_score_count, 0, ',', ' ')}}--}}
                            {{--                                        / {{number_format($songs_count, 0, ',', ' ')}}</b></td>--}}

                            {{--                                <td></td>--}}

                            {{--                            </tr>--}}


                            {{--                            <tr>--}}
                            {{--                                <td>Kompletní písně</td>--}}
                            {{--                                <td style="width: 50%">--}}
                            {{--                                    <div class="progress rounded"--}}
                            {{--                                         style="height: 15px">--}}
                            {{--                                        <div class="progress-bar rounded bg-primary"--}}
                            {{--                                             role="progressbar"--}}
                            {{--                                             style="width: {{round(($songs_w_all_count/$songs_count)*100)}}%;"--}}
                            {{--                                             aria-valuenow="{{round(($songs_w_all_count/$songs_count)*100)}}"--}}
                            {{--                                             aria-valuemin="0"--}}
                            {{--                                             aria-valuemax="100"></div>--}}
                            {{--                                    </div>--}}
                            {{--                                </td>--}}
                            {{--                                <td>{{round(($songs_w_all_count/$songs_count)*100)}}&nbsp;%</td>--}}
                            {{--                                <td>--}}
                            {{--                                    <b>{{number_format($songs_w_all_count, 0, ',', ' ')}}--}}
                            {{--                                        / {{number_format($songs_count, 0, ',', ' ')}}</b>--}}
                            {{--                                </td>--}}
                            {{--                            </tr>--}}


                            <tr>
                                <td><a href="{{route('admin.author.index')}}">Autoři</a></td>
                                <td colspan="4"><b>{{number_format($authors_count, 0, ',', ' ')}}</b></td>
                            </tr>
                            <tr>
                                <td><a href="{{route('admin.external.index')}}">Materiály</a></td>
                                <td colspan="4"><b>{{number_format($externals_count, 0, ',', ' ')}}</b></td>
                            </tr>
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
                                        <h5 class="card-title">Regenschori</h5>
                                        <p class="card-text text-muted">informace o projektu</p>
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
                             style="width: 150px"
                             class="rounded-circle"
                             alt="avatar">

                        <br>

                        <h3>{{ Auth::user()->name }}</h3>
                        {{--                        @if (Auth::user()->roles()->count() > 0)--}}
                        {{--                            <span>({{Auth::user()->roles()->first()->name}})</span>--}}
                        {{--                        @endif--}}
                        <h4 class="mx-2 text-secondary"></h4>


                        <div class="card">
                            <div class="card-body mb-0">

                                <span>Díky za dobře odvedenou práci!</span>
                                <br>Tvé příspěvky si zobrazilo
                                <b>
                                    <user-stats user-id="{{ Auth::user()->id }}"
                                                :embedded="true"></user-stats>
                                    lidí</b>.
                            </div>
                        </div>
                    </div>
                </div>

                <user-stats user-id="{{ Auth::user()->id }}"
                            :embedded="false"></user-stats>
            </div>
        </div>
    </div>
@endsection
