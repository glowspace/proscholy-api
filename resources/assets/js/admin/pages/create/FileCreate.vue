<template>
    <v-app :dark="$root.dark">
        <v-container fluid grid-list-xs>
            <h1 class="h2 mb-3">Nahrát nový soubor
                <span v-if="songLyric">
                k <a :href="'/admin/song/' + songLyric + '/edit'">písni č. {{ songLyric }}</a>
                </span>
            </h1>

            <div class="row">
                <div class="col-sm-6">
                    <form action="/admin/file" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="_token" :value="csrf">
                        <div class="input-group mb-3">
                            <input class="form-control" type="file" required autofocus name="filename">
                        </div>
                        <input type="hidden" name="song_lyric_id" :value="songLyric">

                        <v-btn type="submit" name="redirect" value="edit" class="ml-0 success">Uložit a upravit</v-btn>
                        <v-btn type="submit" name="redirect" value="create" class="ml-0">Uložit a nahrát další soubor</v-btn>
                    </form>
                </div>
            </div>
        </v-container>
    </v-app>
</template>

<script>
export default {
    props: ['song-lyric'],

    computed: {
        csrf() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        }
    }
};
</script>
