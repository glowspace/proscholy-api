<template>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ name }}</h5>
            <p class="card-text">{{ info }}</p>
        </div>
    </div>
</template>

<script>
    export default {
        name: "UserCard",

        props: [
            'name',
            'info'
        ]
    }
</script>

<style scoped
       lang="scss">

    @import "~bootstrap/scss/functions";
    @import "~bootstrap/scss/variables";
    @import "~bootstrap/scss/mixins";

    .card {
        flex: 0 0 calc(100% / 3 - 20px);
        margin-left: 10px;
        margin-right: 10px;

        @include media-breakpoint-between(sm, md) {
            flex: 0 0 calc(100% / 2 - 20px);
        }

        @include media-breakpoint-down(xs) {
            margin-bottom: 15px;

            .card-body {
                padding: 1rem;
            }
        }

    }
</style>
