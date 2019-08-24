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
                    <a target="_blank" href="https://docs.google.com/forms/d/e/1FAIpQLSfI0143gkLBtMbWQnSa9nzpOoBNMokZrOIS5mUreSR41E_B7A/viewform?usp=pp_url&entry.791243479=ano" class="btn btn-primary btn-outline stretched-link">Vyplnit formulář</a>
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

        @php
            $our_team = array(
                array('name' => 'Michael Dojčár', 'team' => 'organizace', 'info' => 'celková koordinace projektu, IT'),
                array('name' => 'Miroslav Šerý', 'team' => 'organizace', 'info' => 'vedoucí vývojář, vývoj webu'),
                array('name' => 'Jana Stuchlíková', 'team' => 'organizace', 'info' => 'vedoucí redakce'),
                array('name' => 'P. Ondřej Talaš', 'team' => 'organizace', 'info' => 'koordinace, duchovní podpora, PR'),
                array('name' => 'Ondřej Múčka', 'team' => 'organizace', 'info' => 'Musica Sacra'),
                
                array('name' => 'Miroslav Šerý',    'team' => 'vývoj', 'info' => 'vedoucí vývojář, vývoj webu'),
                array('name' => 'Michael Dojčár', 'team' => 'vývoj', 'info' => 'celková koordinace projektu, IT'),
                array('name' => 'Vít Kološ', 'team' => 'vývoj', 'info' => 'vývoj webu, návrh rozhraní'),
                array('name' => 'Josef Řídký', 'team' => 'vývoj', 'info' => 'mobilní aplikace pro Android'),
                array('name' => 'Patrik Dobiáš', 'team' => 'vývoj', 'info' => 'mobilní aplikace pro iOS'),
                array('name' => 'Benjamín Tichý', 'team' => 'vývoj', 'info' => 'logo, vizuální styl, návrh rozhraní'),
                
                array('name' => 'P. Ondřej Talaš', 'team' => 'public relations', 'info' => 'koordinace, duchovní podpora, PR'),
                array('name' => 'Emma Kasanová', 'team' => 'public relations', 'info' => ''),
                array('name' => 'Martin Tůma', 'team' => 'public relations', 'info' => ''),
                array('name' => 'Petra Kalousková', 'team' => 'public relations', 'info' => ''),
                array('name' => 'Zuzana Haikerová', 'team' => 'public relations', 'info' => ''),
                
                array('name' => 'P. Jan Šlégr', 'team' => 'zástupci z institucí', 'info' => 'Liturgická komise ČBK'),
                array('name' => 'Veronika Lehrlová', 'team' => 'zástupci z institucí', 'info' => 'Sekce pro mládež ČBK'),
                
                array('name' => 'Jana Stuchlíková', 'team' => 'redakce', 'info' => 'vedoucí redakce'),
                array('name' => 'Ondřej Múčka', 'team' => 'redakce', 'info' => 'Musica Sacra'),
                array('name' => 'Václav Šablatura', 'team' => 'redakce', 'info' => 'odborný konzultant'),
                array('name' => 'Anna Berková', 'team' => 'redakce', 'info' => ''),
                array('name' => 'Barbora Kuchaříková', 'team' => 'redakce', 'info' => ''),
                array('name' => 'Dagmar Mojžíšová', 'team' => 'redakce', 'info' => ''),
                array('name' => 'Eliška Plačková', 'team' => 'redakce', 'info' => ''),
                array('name' => 'Jakub Soukal', 'team' => 'redakce', 'info' => ''),
                array('name' => 'Lada Radmacherová', 'team' => 'redakce', 'info' => ''),
                array('name' => 'Martina Petrůjová', 'team' => 'redakce', 'info' => ''),
                array('name' => 'Matěj Kulišťák', 'team' => 'redakce', 'info' => ''),
                array('name' => 'Monika Šinclová', 'team' => 'redakce', 'info' => ''),
                array('name' => 'Tereza Halaštová', 'team' => 'redakce', 'info' => ''),
                array('name' => 'Terezie Kološová', 'team' => 'redakce', 'info' => ''),
                array('name' => 'Terezie Tichá', 'team' => 'redakce', 'info' => ''),
                array('name' => 'Zuzana Bayerová', 'team' => 'redakce', 'info' => ''),
                array('name' => 'Zuzana Haikerová', 'team' => 'redakce', 'info' => ''),
            );
            $teams = array('organizace', 'vývoj', 'public relations', 'zástupci z institucí', 'redakce');
            $number_of_members = count(array_unique(array_column($our_team, 'name')));
            @endphp

            <h3>Náš tým</h3>

            @foreach ($teams as $team)
            <div class="our-team">
                <h4>{{ ucfirst($team) }}</h4>
                @php
                $team_members = array_filter($our_team, function ($var) use ($team) {
                    return ($var['team'] == $team);
                });
                @endphp
            <div class="d-block d-sm-flex flex-wrap">
                @foreach ($team_members as $key => $member)
                <div class="card{{ (array_search($member['name'], array_column($our_team, 'name')) < $key)?' bg-transparent':'' }}">
                    <div class="card-body">
                        <h5 class="card-title {{ ($member['info']=='')?'mb-0':'' }}">{{ $member['name'] }}</h5>
                        <p class="card-text">{{ $member['info'] }}</p>
                    </div>
                </div>
            @endforeach
            </div>
        </div>
        @endforeach
    </div>
@endsection