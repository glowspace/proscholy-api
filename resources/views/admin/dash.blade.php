@extends('layout.admin')

@section('content-withmenu')
    <div class="__container-fluid">
        <h1>Administrace</h1>
        
        <div class="row">
            <div class="col-sm-7">

                <div class="card">
                    <div class="card-header">Odkazy</div>
                    <div class="card-body">
                        <p>Slack (týmová komunikace) <a href="https://proscholy.slack.com" target="_blank">proscholy.slack.com</a> - lepší
                        je stáhnout si app (mobil/PC)</p>
                        <p>Redakce - seznam zpěvníků, autorů - <a href="https://docs.google.com/spreadsheets/d/1iE38u0TeK9nWgYKUZQt4YxnRPP-nL4ogLiplh4TJgk4/edit?usp=sharing" target="_blank">Tabulka Google</a></p>
                        <p>Představení a cíle projektu Regenschori - <a href="https://docs.google.com/document/d/1leyWsVScenFDaYrzhWU487o_TMoCF-42QXvPPVaRo-M/edit?usp=sharing" target="_blank">Dokument Google</a></p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Instruktážní videa od Janey</div>
                    <div class="card-body">
                        <iframe width="560" height="315" src="https://www.youtube.com/embed/videoseries?list=PLXLfC_XTiu7qWXgsf-18mPu-IWFZ5o2xn" frameborder="0" allow=encrypted-media" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        
        <div class="col-sm-5">
                <table class="table table-bordered">
                    <tr>
                        <td>Krizový telefon <br>(urgent. dotazy, závady)</td>
                        <td class="text-danger">+420 734 791 909</td>
                    </tr>
                </table>

                <h4 style="margin-top: 40px">Statistika</h4>

                <table class="table table-bordered">
                    <tr>
                        <td>Písně s textem</td>
                        <td><b>{{$songs_w_text_count}}</b></td>
                    </tr>
                    <tr>
                        <td>Písně celkem</td>
                        <td><b>{{$songs_count}}</b></td>
                    </tr>
                    <tr>
                        <td>Autoři</td>
                        <td><b>{{$authors_count}}</b></td>
                    </tr><tr>
                        <td>Externí odkazy</td>
                        <td><b>{{$externals_count}}</b></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection
