<template>
  <v-hover>
  <div class="hover-container" slot-scope="{ hover }">
    <v-slide-x-reverse-transition>
        <v-chip  class="hover-text" v-if="hover" :color="colors[internalIndex]">{{ hoverTexts[internalIndex] }}</v-chip>
    </v-slide-x-reverse-transition>
    <v-btn :color="colors[internalIndex]" @click="next" 
            v-on:input="updated(song_lyric)"
            class="text-none">
      <slot></slot>
    </v-btn>
  </div>
  </v-hover>
</template>

<style>
.hover-container {
  position: relative;
}

.hover-text {
  position: absolute;
  color: white;
  right: 0;
}
</style>


<script>
export default {
  props: ["colors", "value", "hover-texts"],

  data() {
    return {
      index: this.value ? this.value : 0
    };
  },

  watch: {
    value(val, prev) {
      this.index = this.value ? this.value : 0;
    }
  },

  computed: {
    internalIndex: {
      get() {
        return this.index;
      },
      set(i) {
        this.index = i;
        this.$emit("input", i);
      }
    }
  },

  methods: {
    next(e) {
      this.internalIndex = this.mod(this.internalIndex + 1, this.colors.length);
      // this.$emit("click", e);
    },

    mod(n, m) {
      return ((n % m) + m) % m;
    }
  }
};
</script>
