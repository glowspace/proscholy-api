<template>
    <div>
        <h2>Můj účet</h2>

        <p>Přihlášený uživatel: {{ user.name }}</p>
        <p>Email: {{ user.email }}</p>

        <h2>Zpěvníky:</h2>

        <ul class="list-group">
            <li v-for="songbook in user.songbooks"
                v-bind:key="songbook.id"
                class="list-group-item d-flex justify-content-between align-items-center">
                {{ songbook.name }}
                <span class="badge badge-primary badge-pill">{{ songbook.song_lyrics.length }}</span>
                <ul>
                    <li v-for="song_lyric in songbook.song_lyrics"
                        v-bind:key="song_lyric.id">
                        {{ song_lyric.name }}
                    </li>
                </ul>
            </li>
        </ul>

        <!-- <a @click="addNewSongbook" :disabled="new_songbook_name == ''">Přidat nový zpěvník</a>
        <input type="text" v-model="new_songbook_name"/> -->
    </div>
</template>

<script>
    export default {
        name: "MyAccount"
    }
</script>

<style scoped>

</style>
