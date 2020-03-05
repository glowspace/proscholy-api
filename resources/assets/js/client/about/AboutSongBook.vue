<template>
    <div class="container">
        <h1>O zpěvníku ProScholy.cz</h1>

        <div class="row">
            <div class="card col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                <div class="card-body">
                    <h5 class="card-title">Zpětná vazba</h5>
                    <p class="card-text">
                        Pokud se vám podaří najít si 54 sekund k&nbsp;vyplnění našeho formuláře zpětné vazby, budeme jen
                        rádi.<br>
                        Názor uživatelů je pro nás velmi důležitý a v&nbsp;určitých věcech opravdu směrodatný.
                    </p>
                    <a target="_blank"
                       href="https://docs.google.com/forms/d/e/1FAIpQLSfI0143gkLBtMbWQnSa9nzpOoBNMokZrOIS5mUreSR41E_B7A/viewform?usp=pp_url&entry.791243479=ano"
                       class="btn btn-primary btn-outline stretched-link">Vyplnit formulář</a>
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
            Můžete nám napsat na email <a href="mailto:redakce@proscholy.cz">redakce@proscholy.cz</a> nebo se na nás
            obrátit telefonicky na <a href="tel:+420734791909">734 791 909</a>.
        </p>

        <p>
            <span class="mr-2">Pokud byste nám chtěli poslat námět na novou píseň do zpěvníku, máme pro vás připravený formulář: </span>
            <a class="btn btn-outline-primary mb-0"
               href="https://forms.gle/AYXXxkWtDHQQ13856"
               target="_blank">Přidat píseň</a>
        </p>


        <h3>Náš tým</h3>


        <div class="our-team">
            <h4>Organizace</h4>

            <div class="d-block d-sm-flex flex-wrap">

                <user-card name="Michael Dojčár"
                           info="celková koordinace projektu, IT"/>
                <user-card name="Miroslav Šerý"
                           info="vedoucí vývojář, vývoj webu"/>
                <user-card name="Jana Stuchlíková"
                           info="vedoucí redakce"/>
                <user-card name="P. Ondřej Talaš"
                           info="koordinace, duchovní podpora, PR"/>
                <user-card name="Ondřej Múčka"
                           info="Musica Sacra"/>
                <user-card name="Zuzana Haikerová"
                           info="vedoucí propagace"/>

            </div>
        </div>

        <div class="our-team">
            <h4>Redakce</h4>

            <div class="d-block d-sm-flex flex-wrap">

                <user-card name="Jana Stuchlíková"
                           info="vedoucí redakce"/>
                <user-card name="Ondřej Múčka"
                           info="Musica Sacra"/>
                <user-card name="Václav Šablatura"
                           info="odborný konzultant"/>
                <user-card name="Barbora Kuchaříková"
                           info=""/>
                <user-card name="Jakub Soukal"
                           info=""/>
                <user-card name="Lada Radmacherová"
                           info=""/>
                <user-card name="Martina Petrůjová"
                           info=""/>
                <user-card name="Matěj Kulišťák"
                           info=""/>
                <user-card name="Monika Šinclová"
                           info=""/>
                <user-card name="Tereza Halaštová"
                           info=""/>
                <user-card name="Terezie Kološová"
                           info=""/>
                <user-card name="Terezie Tichá"
                           info=""/>
                <user-card name="Zuzana Bayerová"
                           info=""/>
                <user-card name="Zuzana Haikerová"
                           info=""/>

            </div>
        </div>

        <div class="our-team">
            <h4>Vývoj</h4>

            <div class="d-block d-sm-flex flex-wrap">

                <user-card name="Miroslav Šerý"
                           info="vedoucí vývojář, vývoj webu"/>
                <user-card name="Michael Dojčár"
                           info="celková koordinace projektu, IT"/>
                <user-card name="Vít Kološ"
                           info="vývoj webu, návrh rozhraní"/>
                <user-card name="Josef Řídký"
                           info="mobilní aplikace pro Android"/>
                <user-card name="Patrik Dobiáš"
                           info="mobilní aplikace pro iOS"/>
                <user-card name="Benjamín Tichý"
                           info="logo, design, návrh rozhraní"/>

            </div>
        </div>


        <div class="our-team">
            <h4>Public relations</h4>

            <div class="d-block d-sm-flex flex-wrap">

                <user-card name="Zuzana Haikerová"
                           info="vedoucí propagace"/>
                <user-card name="P. Ondřej Talaš"
                           info="koordinace, duchovní podpora, PR"/>
                <user-card name="Emma Kasanová"
                           info=""/>
                <user-card name="Patra Kalousková"
                           info=""/>
                <user-card name="Martin Tůma"
                           info=""/>

            </div>
        </div>

        <div class="our-team">
            <h4>Zástupci z institucí</h4>

            <div class="d-block d-sm-flex flex-wrap">

                <user-card name="P. Jan Šlégr"
                           info="Liturgická komise ČBK"/>
                <user-card name="Veronika Lehrlová"
                           info="Sekce pro mládež ČBK"/>

            </div>
        </div>


    </div>

</template>

<script>
    import UserCard from "./UserCard";

    export default {
        name: "AboutSongBook",
        components: {UserCard},
    }
</script>

<style scoped
       lang="scss">


    .our-team {
        h4 {
            transition: padding 0.2s;
        }

        &:hover h4 {
            padding-left: 0.5rem;
        }

        .flex-wrap {
            margin: 0 -10px;
        }
    }

</style>
