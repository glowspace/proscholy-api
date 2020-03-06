<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark justify-content-between absolute-top">
            <div class="container">
                <router-link to="/" class="navbar-brand">
                    <img src="img/logo_v2.png"
                         style="padding: 0 10px 0 0;"
                         width="60"
                         alt="logo">Zpěvník pro scholy</router-link>

                <div class="d-inline-flex">
                    <router-link to="/"
                                 class="btn btn-secondary"><i class="fas fa-search"></i> Vyhledávání
                    </router-link>

                    <router-link to="/o-zpevniku"
                       class="btn btn-secondary">
                        <i class="fas fa-info"></i> O zpěvníku</router-link>

                    <dark-mode-button v-cloak> Tmavý mód</dark-mode-button>
                </div>

                <div class="d-inline-flex">
                    <a href=""
                       class="btn btn-secondary"><i class="fas fa-user"></i> Uživatel</a>
                </div>
            </div>
        </nav>

        <a class="invisible btn btn-secondary mobile-padding-button mb-0"><i class="fas fa-search"></i></a>

        <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
            <div class="container">
                <router-link to="/"
                   class="btn">
                    <img src="img/logo_v2.png"
                         height="24"
                         alt="logo"></router-link>
                <router-link to="/"
                   class="btn btn-secondary"><i class="fas fa-search"></i></router-link>

                <router-link to="/o-zpevniku"
                   class="btn btn-secondary"><i class="fas fa-info"></i></router-link>

                <dark-mode-button></dark-mode-button>
            </div>
        </nav>
    </div>
</template>

<script>
    import DarkModeButton from "../../components/DarkModeButton";

    export default {
        name: "Navbar",

        components: {DarkModeButton}
    }
</script>

<style scoped>

</style>
