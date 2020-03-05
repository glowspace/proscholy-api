<template>
    <div>
        <div class="pt-5 pb-3 h2 mb-0 invisible">
            <div><i class="fab fa-android"></i></div>
            <div><i class="fab fa-apple"></i></div>
        </div>

        <div class="text-center pt-5 text-white app-download pb-3 h2 mb-0">
            <div>
                <a href="http://play.google.com/store/apps/details?id=jozkar.mladez"
                   target="_blank"><i class="fab fa-android"></i></a>
                <span class="btn px-2 font-weight-bold">APLIKACE</span>
                <a href="http://itunes.apple.com/app/zpěvník-pro-scholy/id1475375453"
                   target="_blank"><i class="fab fa-apple"></i></a>
            </div>
            <div>
                <a href="https://www.facebook.com/proscholy/"
                   target="_blank"><i class="fab fa-facebook"></i></a>
                <span class="btn px-2 font-weight-bold">SOCIÁLNÍ SÍTĚ</span>
                <a href="https://www.instagram.com/zpevnik.proscholy.cz/"
                   target="_blank"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "AppLinks"
    }
</script>

<style scoped>
    .app-download {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
    }

    .app-download div * {
        color: white !important;
    }

    .app-download span.btn {
        background-color: transparent !important;
        cursor: initial !important;
    }
</style>
