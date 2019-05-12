<template>
    <div>
        <button-color-toggle 
            v-for="song_lyric in value" 
            v-bind:key="song_lyric.id" :colors="colors"
            v-model="song_lyric.type"
            v-on:click="updated(song_lyric)">
            {{ song_lyric.name }}
        </button-color-toggle>
    </div>
</template>


<script>
import ButtonColorToggle from "../components/ButtonColorToggle.vue";

export default {
  components: {
    ButtonColorToggle
  },

  props: ["value"],

  data() {
    return {
      colors: ["info", "success", "warning"],
      // types - 0: original 1: translation 2: authorized translation
    };
  },
  
  methods: {
      updated(last) {
          // check the consistency
      }
  }
};
</script>
