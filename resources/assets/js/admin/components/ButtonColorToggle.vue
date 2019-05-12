<template>
  <v-btn :color="colors[internalIndex]" @click="next"><slot></slot></v-btn>
</template>

<script>
export default {
  props: ["colors", "value"],

  data() {
      return {
          index: this.value ? this.value : 0
      }
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
