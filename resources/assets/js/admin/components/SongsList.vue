<template>
  <div class="users-list">
    <!-- Apollo Query -->
    <ApolloQuery :query="gql`
      query FetchSongLyrics {
        song_lyrics {
          id
          name  
        }
      }`">
      <!-- The result will automatically updated -->
      <template slot-scope="{ result: { data, loading } }">
        <!-- Some content --> 
        <div v-if="loading">Loading...</div>
        <ul v-else>
          <li v-for="song_lyric of data.song_lyrics" class="song_lyric" :key="song_lyric.id">
            {{ song_lyric.name }}
          </li>
        </ul>
      </template>
    </ApolloQuery>
  </div>
</template>

<!-- No need for script -->

<style scoped>
  .song_lyric {
    list-style: none;
    padding: 12px;
    color: blue;
  }
</style>

<script>
export default {
  
}
</script>
