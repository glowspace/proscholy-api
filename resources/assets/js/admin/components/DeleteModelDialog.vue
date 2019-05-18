<template>
  <v-dialog v-model="dialog" persistent max-width="300px">
    <template v-slot:activator="{ on }">
      <v-btn color="error" dark v-on="on"><slot></slot></v-btn>
    </template>
    <v-card>
      <v-card-title class="headline">Vymazat ...</v-card-title>
      <v-card-text>Opravdu chcete vymazat ... ?</v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn outline @click="onCancel" class="text-none">Zrušit</v-btn>
        <v-btn class="error" @click="onConfirm">Vymazat</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import gql, { disableFragmentWarnings } from "graphql-tag";

const DELETE_MODEL_DATABASE = gql`
  mutation($input: DeleteModelInput!) {
    delete_model(input: $input) {
      id
    }
  }
`;

export default {
  props: ["class-name", "model-id"],

  data() {
    return {
    //   id: undefined,
      dialog: false
    };
  },

  methods: {
    destroy() {
      // mutate
      this.$apollo.mutate({
          mutation: DELETE_MODEL_DATABASE,
          variables: {
              input: {
                  class_name: this.className,
                  id: this.modelId
              }
          }
      }).then(result => {
          if (result.data.delete_model) {
              this.$emit("deleted", result.data.delete_model);
          } else {
              this.$emit("error", "Daná položka už byla vymazána");
          }
      }).catch(error => {
          // error
          this.$emit("error", error);
      });
    },

    onCancel() {
      this.dialog = false;
      this.$emit("cancelled", this.modelId);
    },

    onConfirm() {
      this.dialog = false;
      this.destroy();
    }
  }
};
</script>

