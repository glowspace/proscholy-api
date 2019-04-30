<template>
<!-- v-app must wrap all the components -->
  <v-app>
    <v-data-table
      :headers="headers"
      :items="song_lyrics"
      class="users-list">
      
      <template v-slot:items="props">
        <td>{{ props.item.name }}</td>
        <td>{{ props.item.is_original }}</td>
        <td>{{ props.item.is_published }}</td>
        <td>{{ props.item.is_approved_by_author }}</td>
        <td>Akce smazat</td>
      </template>
    </v-data-table>
  </v-app>
</template>

<style>

</style>

<script>

import gql from 'graphql-tag';

const fetch_song_lyrics = gql`
        query FetchSongLyrics {
            song_lyrics {
                id,
                name,
                is_original,
                is_published,
                is_approved_by_author
            }
        }`;

export default {
  data() {
    return {
      headers: [
        { text: 'Název písničky', value: 'name' },
        { text: 'Typ', value: 'is_original' },
        // { text: 'Naposledy upraveno', value: 'updated_at' },
        { text: 'Publikováno', value: 'is_published' },
        { text: 'Schváleno autorem', value: 'is_approved_by_author' },
        { text: 'Akce', value: 'action' },
      ],
    }
  },

  apollo: {
    // song_lyrics: {
    //   query: fetch_song_lyrics,
    //   result(data) {
    //     this.song_lyrics = _.cloneDeep(data.song_lyrics)
    //   }
    // }
    song_lyrics: fetch_song_lyrics
  }
}
</script>
