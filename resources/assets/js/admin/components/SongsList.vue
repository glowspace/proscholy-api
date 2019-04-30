<template>
<!-- v-app must wrap all the components -->
  <v-app>
    <v-data-table
      :headers="headers"
      :items="song_lyrics"
      class="users-list">
      
      <template v-slot:items="props">
        <td>{{ props.item.name }}</td>
      </template>
    </v-data-table>
  </v-app>
</template>

<style>
</style>

<script>
export default {
  data() {
    return {
      headers: [
        { text: 'Radek 1', value: 'name' }
      ],
      song_lyrics: [
        {
          name: 'Ahoj'
        },
        {
          name: 'zdar'
        }
      ]
    }
  }
}
</script>
