<template>
  <a class="btn create-song" v-show="name" @click="submit(true)">
    {{ label }}
  </a>
</template>

<style lang="scss">
  .btn.create-song {
    color: white !important;
    text-transform: none;
  }
</style>

<script>
import gql, { disableFragmentWarnings } from "graphql-tag";

const CREATE_MODEL_MUTATION = gql`
  mutation($input: CreateModelInput!) {
    create_model(input: $input) {
      id
      edit_url
    }
  }
`;

export default {
  props: ["name"],

  data() {
    return {
      label: ""
    }
  },

  methods: {
    submit(redir) {
      this.$apollo
        .mutate({
          mutation: CREATE_MODEL_MUTATION,
          variables: {
            input: {
              required_attribute: this.name,
              class_name: "SongLyric"
            }
          }
        })
        .then(result => {
          if (redir) {
            window.location.href = result.data.create_model.edit_url;
          } else {
            this.label = "Píseň " + this.name + "byla vytvořena";
          }
        })
        .catch(error => {
          if (!error.graphQLErrors || error.graphQLErrors.length == 0) {
            console.log(error.graphQLErrors);
          } else {
            this.label = error.graphQLErrors[0].extensions.validation.required_attribute[0];
          }
        });
    }
  },

  watch: {
    name() {
      this.label = "Vytvořit novou píseň s názvem " + this.name
    }
  }
};
</script>