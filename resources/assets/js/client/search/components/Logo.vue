<template>
    <div class="logo-wrapper">
        <div class="logo"></div>
        <span class="caption noselect">Zpěvník</span>
    </div>
</template>

<script>
    export default {
        name: "Logo"
    }
</script>

<style scoped
       lang="scss">
    @import "~bootstrap/scss/functions";
    @import "~bootstrap/scss/variables";
    @import "~bootstrap/scss/mixins";


    .logo-wrapper {
        text-align: center;
        margin-top: 130px;
        transition: 0.5s;
        height: 160px;
        margin-right: -15px;
        margin-left: -15px;

        @include media-breakpoint-down(sm) {
            margin-top: 110px;
            margin-bottom: -50px;
            height: 90px;
        }

        @include media-breakpoint-down(xs) {
            margin-top: 90px;
            margin-bottom: -70px;
            height: 70x;
        }

        .logo {
            height: 130px;
            width: 130px;

            display: inline-block;
            position: relative;
            top: 27px;

            background-image: url(/img/logo_bubble.svg);
            background-position: center;
            background-size: 149%;

            @include media-breakpoint-down(sm) {
                top: 2px;
                height: 50px;
                width: 50px;

                margin-right: 6px;
            }

            @include media-breakpoint-down(xs) {
                top: 0;
                height: 30px;
                width: 30px;

                margin-right: 3px;
            }
        }

        .caption {
            font-size: 118px;
            color: white;

            line-height: 160px;

            font-family: "Cocogoose", "Arial Black", sans-serif;

            @include media-breakpoint-down(sm) {
                font-size: 60px;
                line-height: 60px;
            }

            @include media-breakpoint-down(xs) {
                font-size: 40px;
                line-height: 40px;
            }
        }
    }
</style>
