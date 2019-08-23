@extends('layout.client')

@section('content')
    <div class="container">
        <h1>O zpěvníku ProScholy.cz</h1>

        <div class="row">
            <div class="card col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                <div class="card-body">
                    <h5 class="card-title">Zpětná vazba</h5>
                    <p class="card-text">
                        Pokud se vám podaří najít si 54 sekund k&nbsp;vyplnění našeho formuláře zpětné vazby, budeme jen rádi.<br>
                        Názor uživatelů je pro nás velmi důležitý a v&nbsp;určitých věcech opravdu směrodatný.
                    </p>
                    <a href="https://docs.google.com/forms/d/e/1FAIpQLSfI0143gkLBtMbWQnSa9nzpOoBNMokZrOIS5mUreSR41E_B7A/viewform?usp=pp_url&entry.791243479=ano" class="btn btn-primary btn-outline stretched-link">Vyplnit formulář</a>
                </div>
            </div>
        </div>

        <h2>Kdo za projektem stojí?</h2>

        <p>Jsme skupina lidí, kteří chtějí usnadnit práci všem,
            kteří pracují s českou nebo slovenskou křesťanskou tvorbou.
            V dnešní době, kdy jsou materiály i potřebné know-how roztroušeny po mnoha různých místech
            na internetu nebo v papírových sbornících a zpěvnících,
            chceme nabídnout rychlou a praktickou alternativu.
        </p>

        <p>
            Chcete se podílet na tvorbě zpěvníku?
            Máte tipy na nové funkce nebo nový repertoár?<br>
            Můžete nám napsat na email <a href="mailto:redakce@proscholy.cz">redakce@proscholy.cz</a> nebo se na nás obrátit telefonicky na <a href="tel:+420734791909">734 791 909</a>.
        </p>

        <h3>Náš tým</h3>

        @php
            $our_team = array(
                array('name' => 'Michael Dojčár', 'team1' => 'organizace', 'team2' => 'organizace, vývoj', 'info' => 'celková koordinace projektu, IT'),
                array('name' => 'Miroslav Šerý', 'team1' => 'organizace', 'team2' => 'vývoj', 'info' => 'vedoucí vývojář, vývoj webu'),
                array('name' => 'Jana Stuchlíková', 'team1' => 'organizace', 'team2' => 'redakce', 'info' => 'vedoucí redakce'),
                array('name' => 'Ondřej Talaš', 'team1' => 'organizace', 'team2' => 'public relations', 'info' => 'koordinace, duchovní podpora'),
                array('name' => 'Ondřej Múčka', 'team1' => 'organizace', 'team2' => 'redakce', 'info' => 'Musica Sacra'),

                array('name' => 'Miroslav Šerý',    'team1' => 'vývoj', 'team2' => 'organizace', 'info' => 'vedoucí vývojář, vývoj webu'),
                array('name' => 'Michael Dojčár', 'team1' => 'vývoj', 'team2' => '', 'info' => 'celková koordinace projektu, IT'),
                array('name' => 'Vít Kološ', 'team1' => 'vývoj', 'team2' => '', 'info' => 'vývoj webu, návrh rozhraní'),
                array('name' => 'Josef Řídký', 'team1' => 'vývoj', 'team2' => '', 'info' => 'mobilní aplikace pro Android'),
                array('name' => 'Patrik Dobiáš', 'team1' => 'vývoj', 'team2' => '', 'info' => 'mobilní aplikace pro iOS'),
                array('name' => 'Benjamín Tichý', 'team1' => 'vývoj', 'team2' => 'grafika', 'info' => 'logo, vizuální styl, návrh rozhraní'),

                array('name' => 'Ondřej Talaš', 'team1' => 'public relations', 'team2' => 'organizace', 'info' => 'koordinace, duchovní podpora'),
                array('name' => 'Emma Kasanová', 'team1' => 'public relations', 'team2' => '', 'info' => ''),
                array('name' => 'Martin Tůma', 'team1' => 'public relations', 'team2' => '', 'info' => ''),
                array('name' => 'Petra Kalousková', 'team1' => 'public relations', 'team2' => '', 'info' => ''),
                array('name' => 'Zuzana Haikerová', 'team1' => 'public relations', 'team2' => 'redakce', 'info' => ''),

                array('name' => 'o. Jan Šlégr', 'team1' => 'zástupci z institucí', 'team2' => '', 'info' => 'Liturgická komise ČBK'),
                array('name' => 'Veronika Lehrlová', 'team1' => 'zástupci z institucí', 'team2' => '', 'info' => 'Sekce pro mládež ČBK'),

                array('name' => 'Jana Stuchlíková', 'team1' => 'redakce', 'team2' => 'organizace', 'info' => 'vedoucí redakce'),
                array('name' => 'Ondřej Múčka', 'team1' => 'redakce', 'team2' => 'organizace', 'info' => 'Musica Sacra'),
                array('name' => 'Václav Šablatura', 'team1' => 'redakce', 'team2' => '', 'info' => 'odborný konzultant'),
                array('name' => 'Anežka Pobořilová', 'team1' => 'redakce', 'team2' => '', 'info' => ''),
                array('name' => 'Anna Berková', 'team1' => 'redakce', 'team2' => '', 'info' => ''),
                array('name' => 'Barbora Kuchaříková', 'team1' => 'redakce', 'team2' => '', 'info' => ''),
                array('name' => 'Dáška Mojžíšová', 'team1' => 'redakce', 'team2' => '', 'info' => ''),
                array('name' => 'David Indra', 'team1' => 'redakce', 'team2' => '', 'info' => ''),
                array('name' => 'Eliška Plačková', 'team1' => 'redakce', 'team2' => '', 'info' => ''),
                array('name' => 'Jakub Soukal', 'team1' => 'redakce', 'team2' => '', 'info' => ''),
                array('name' => 'Martina Petrůjová', 'team1' => 'redakce', 'team2' => '', 'info' => ''),
                array('name' => 'Matěj Kulišťák', 'team1' => 'redakce', 'team2' => '', 'info' => ''),
                array('name' => 'Monika Šinclová', 'team1' => 'redakce', 'team2' => '', 'info' => ''),
                array('name' => 'Tereza Halaštová', 'team1' => 'redakce', 'team2' => '', 'info' => ''),
                array('name' => 'Terezie Kološová', 'team1' => 'redakce', 'team2' => '', 'info' => ''),
                array('name' => 'Terezie Tichá', 'team1' => 'redakce', 'team2' => '', 'info' => ''),
                array('name' => 'Zuzana Bayerová', 'team1' => 'redakce', 'team2' => '', 'info' => ''),
                array('name' => 'Zuzana Haikerová', 'team1' => 'redakce', 'team2' => 'public relations', 'info' => ''),
            );
            $teams = array('organizace', 'vývoj', 'public relations', 'zástupci z institucí', 'redakce');
        @endphp

        @foreach ($teams as $team)
        <div class="our-team">
            <h4>{{ ucfirst($team) }}</h4>
            @php
                $team_members = array_filter($our_team, function ($var) use ($team) {
                    return ($var['team1'] == $team);
                });
            @endphp
            <div class="card-columns">
            @foreach ($team_members as $member)
                <div>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title {{ ($member['info'].$member['team2']=='')?'mb-0':'' }}">{{ $member['name'] }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted {{ ($member['team2']=='')?'d-none':'' }}">{{ $member['team2'] }}</h5>
                            <p class="card-text">{{ $member['info'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
        </div>
        @endforeach
    </div>
@endsection