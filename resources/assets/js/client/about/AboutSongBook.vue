<template>
    <div>

    </div>
</template>

<script>
    export default {
        name: "AboutSongBook"
    }
</script>

<style scoped
       lang="scss">

    @import "~bootstrap/scss/functions";
    @import "~bootstrap/scss/variables";
    @import "~bootstrap/scss/mixins";

    .our-team {
        h4 {
            transition: padding 0.2s;
        }

        &:hover h4 {
            padding-left: 0.5rem;
        }

        .flex-wrap {
            margin: 0 -10px;

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
        }
    }

</style>
