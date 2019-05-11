<template>
  <v-app>
    <notifications/>
    <v-container grid-list-xs>
      <v-layout row>
        <v-flex xs12 md6>
          <v-form ref="form">
            <v-text-field
              label="Jméno autora"
              required
              v-model="name"
              data-vv-name="input.name"
              :error-messages="errors.collect('input.name')"
            ></v-text-field>
            <v-select :items="type_values" v-model="type" label="Typ"></v-select>
            <v-textarea
              name="input-7-4"
              label="Popis autora"
              v-model="description"
              data-vv-name="input.description"
              :error-messages="errors.collect('input.description')"
            ></v-textarea>

            <v-btn @click="submit">Uložit</v-btn>
          </v-form>
        </v-flex>
        <v-flex xs12 md6></v-flex>
      </v-layout>
    </v-container>
  </v-app>
</template>

<script>
import gql from "graphql-tag";
import fragment from "@/graphql/client/author_fragment.graphql";

const fetch_item = gql`
  query($id: ID!) {
    author(id: $id) {
      ...AuthorFragment
      type_string_values
    }
  }
  ${fragment}
`;

const update_item = gql`
  mutation($input: UpdateAuthorInput!) {
    update_author(input: $input) {
      ...AuthorFragment
    }
  }
  ${fragment}
`;

export default {
  props: ["preset-id"],

  data() {
    return {
      id: undefined,
      type: undefined,
      type_values: [],
      description: "",
      name: "",
      err: ""
    };
  },

  apollo: {
    author: {
      query: fetch_item,
      variables() {
        return {
          id: this.id
        };
      },
      result: function result(result) {
        let author = result.data.author;
        // load the requested fields to the vue data property
        this.getFieldsFromFragment(false).forEach(field => {
          this[field] = author[field];
        })
        this.type_values = author.type_string_values.map((val, index) => {
          return { value: index, text: val };
        });
      }
    }
  },

  $_veeValidate: {
    validator: "new"
  },

  mounted() {
    this.id = this.presetId;
  },

  computed: {},

  methods: {
    submit() {
      this.$apollo
        .mutate({
          mutation: update_item,
          variables: {
            input: {
              id: this.id,
              name: this.name,
              description: this.description,
              type: this.type
            }
          }
        })
        .then(result => {
          this.$validator.errors.clear();
          this.$notify({
            title: "Úspěšně uloženo :)",
            text: "Autor byl úspěšně uložen",
            type: "success"
          });
        })
        .catch(error => {
          this.$validator.errors.clear();

          if (error.graphQLErrors.length == 0) {
            // unknown error happened
            this.$notify({
              title: "Chyba při ukládání",
              text: "Uživatel nebyl uložen",
              type: "error"
            });
            return;
          }

          let errorFields = error.graphQLErrors[0].extensions.validation;

          for (const [key, value] of Object.entries(errorFields)) {
            this.$validator.errors.add({ field: key, msg: value });
          }
        });
    },

    // helper method to load field names defined in fragment graphql definition
    getFieldsFromFragment(includeId) {
      let fieldDefs = fragment.definitions[0].selectionSet.selections;

      let fieldNames = fieldDefs.map(field => {
        return field.name.value;
      });

      if (!includeId)
        fieldNames = fieldNames.filter(field => {return field != "id"});

      return fieldNames;
    }
  }
};
</script>
