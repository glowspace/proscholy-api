<template>
    <div>
        <button-color-toggle 
            v-for="song_lyric in value" 
            v-bind:key="song_lyric.id" :colors="colors"
            v-model="song_lyric.type"
            v-on:input="updated(song_lyric)">
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
    //   lazyValue: this.value
    };
  },

//   computed: {
//       song_lyrics: {
//           get() {
//               return this.lazyValue;
//           },
//           set(val) {
//               this.lazyValue = val;
//           }
//       }
//   },

  methods: {
      updated(last) {
          // check the consistency
          if (last.type === 0) {
              console.log("aj");
              // allow only one original -> set other originals to translation
              for (var song_lyric of this.value) {
                  if (song_lyric.type == 0 && song_lyric.id !== last.id) {
                      Vue.set(song_lyric, "type", 1);
                  }
              }
          }
      }
  }
};
</script>
