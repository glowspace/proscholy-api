@extends('layout.master')

@section('navbar')
    @include('client.components.menu_main')
@endsection

@section('content')
    <div class="content-padding">
        <h1>Redakce ProScholy.cz</h1>

        <p>Jsme skupina lidí, kteří chtějí usnadnit práci všem,
            kteří pracují s českou nebo slovenskou křesťanskou tvorbou.
            V dnešní době, kdy je duchovní hudba roztroušena po mnoha různých místech
            na internetu nebo v papírových sbornících a zpěvnících,
            chceme nabídnout rychlou a praktickou alternativu.
        </p>

        <p>
            Chcete se podílet na tvorbě zpěvníku?
            Máte tipy na nové funkce nebo nový repertoár?
            Můžete nám napsat na email redakce@proscholy.cz.</p>

        <h3>Náš redakční tým</h3>

        <div class="row">
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Michael Dojčár</h5>
                        <p class="card-text">koordinace projektu, IT</p>
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
                        <h5 class="card-title">Benjamín Tichý</h5>
                        <p class="card-text">grafika</p>
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
        </div>
    </div>
@endsection