<template>
    <div>
        <v-btn @click="move(-1)">Předchozí uživatel</v-btn>
        <v-btn @click="move(1)">Další uživatel</v-btn>
    </div>
</template>

<script>
import gql from "graphql-tag";

export default {
  props: ["id", "table-name"],

  apollo: {
    items: {
      query: gql`
        query {
          ${this.tableName} {
            id
          }
        }`
    }
  },

  methods: {
    move(diff) {
      let index;

      for (const [key, value] of Object.entries(this.authors)) {
        if (value.id == this.id) {
          index = Number(key);
          break;
        }
      }

      console.log(index);
      // js % modulo is keeping the negative numbers
      index = this.mod((index + diff), this.authors.length);
      console.log(index);

      this.id = this.authors[index].id;
      this.$validator.errors.clear();
    },

    mod(n, m) {
      return ((n % m) + m) % m;
    }
  }
};
</script>
