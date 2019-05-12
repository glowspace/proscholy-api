<template>
    <div>
        <button-color-toggle 
            v-for="song_lyric in song_lyrics_typed" 
            v-bind:key="song_lyric.id" :colors="colors"
            v-model="song_lyric.type">
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

  computed: {
    song_lyrics_typed: {
      get() {
        return this.value.map(song_lyric => {
              var mapped = { id: song_lyric.id, name: song_lyric.name };

              if (song_lyric.is_original) {
                  mapped.type = 0;
              } else {
                  if (song_lyric.is_authorized) {
                      mapped.type = 2;
                  } else {
                      mapped.type = 1;
                  }
              }

              return mapped;
          });
      },
      set(val) {
        this.$emit("input", val);
      }
    }
  },


//   computed: {
//     internalValue: {
//       get() {
//         return this.lazyValue;
//       },
//       set(val) {
//         // this.lazyValue = val;
//         this.$emit("input", val);
//       }
//     }
//   },
};
</script>
