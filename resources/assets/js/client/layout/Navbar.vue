<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark justify-content-between absolute-top">
            <div class="container">
                <a class="navbar-brand"
                   href="/">
                    <img src="img/logo_v2.png"
                         style="padding: 0 10px 0 0;"
                         width="60"
                         alt="logo">Zpěvník pro scholy</a>

                <div class="d-inline-flex">
                    <a href=""
                       class="btn btn-secondary"><i class="fas fa-search"></i> Vyhledávání</a>

                    <a href=""
                       class="btn btn-secondary">
                        <i class="fas fa-info"></i> O zpěvníku</a>

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
                <a href="/"
                   class="btn">
                    <img src="img/logo_v2.png"
                         height="24"
                         alt="logo"></a>
                <a href="/"
                   class="btn btn-secondary"><i class="fas fa-search"></i></a>

                <a href=""
                   class="btn btn-secondary"><i class="fas fa-info"></i></a>

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
