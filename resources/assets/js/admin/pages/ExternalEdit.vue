<template>
  <v-app>
    <notifications/>
    <v-container grid-list-xs>
      <v-layout row>
        <v-flex xs12 md6>
          <v-form ref="form">
            <v-text-field
              label="Url odkaz"
              required
              v-model="model.url"
              data-vv-name="input.url"
              :error-messages="errors.collect('input.url')"
            ></v-text-field>
            <v-select :items="type_values" v-model="model.type" label="Typ"></v-select>

            <v-btn @click="submit" :disabled="!isDirty">Uložit</v-btn>
          </v-form>
        </v-flex>
        <v-flex xs12 md6></v-flex>
      </v-layout>
    </v-container>
  </v-app>
</template>

<script>
import gql from "graphql-tag";
import fragment from "@/graphql/client/external_fragment.graphql";

const FETCH_MODEL_DATABASE = gql`
  query($id: ID!) {
    model_database: external(id: $id) {
      ...ExternalFillableFragment
      type_string_values
    }
  }
  ${fragment}
`;

const MUTATE_MODEL_DATABASE = gql`
  mutation($input: UpdateExternalInput!) {
    update_external(input: $input) {
      ...ExternalFillableFragment
    }
  }
  ${fragment}
`;

export default {
  props: ["preset-id"],

  data() {
    return {
      model: {
        // here goes the definition of model attributes 
        // should match the definition in its ModelFillableFragment in (see graphql/client/model_fragment.graphwl)
        id: undefined,
        url: undefined,
        type: undefined,
      },
      type_values: [],
    };
  },

  apollo: {
    model_database: {
      query: FETCH_MODEL_DATABASE,
      variables() {
        return {
          id: this.model.id
        };
      },
      result(result) {
        let external = result.data.model_database;
        // load the requested fields to the vue data.model property
        for (let field of this.getFieldsFromFragment(false)) {
          Vue.set(this.model, field, external[field]);
        }

        this.type_values = external.type_string_values.map((val, index) => {
          return { value: index, text: val };
        });
      }
    }
  },

  $_veeValidate: {
    validator: "new"
  },

  mounted() {
    this.model.id = this.presetId;

    // prevent user to leave the form if dirty
    window.onbeforeunload = e => {
      if (this.isDirty) {
        e.preventDefault();
        e.returnValue = '';
      }
    };
  },

  computed: {
    isDirty() {
      if (!this.model_database)
        return false;

      for (let field of this.getFieldsFromFragment(this)) {
        if (this.model[field] !== this.model_database[field])
          return true;
      }

      return false;
    }
  },

  methods: {
    submit() {
      this.$apollo
        .mutate({
          mutation: MUTATE_MODEL_DATABASE,
          variables: { input: this.model }
        })
        .then(result => {
          this.$validator.errors.clear();
          this.$notify({
            title: "Úspěšně uloženo :)",
            text: "Externí odkaz byl úspěšně uložen",
            type: "success"
          });
        })
        .catch(error => {
          if (error.graphQLErrors.length == 0) {
            // unknown error happened
            this.$notify({
              title: "Chyba při ukládání",
              text: "Externí odkaz nebyl uložen",
              type: "error"
            });
            return;
          }

          let errorFields = error.graphQLErrors[0].extensions.validation;

          // clear the old errors and (add new ones if exist)
          this.$validator.errors.clear();
          for (const [key, value] of Object.entries(errorFields)) {
            this.$validator.errors.add({ field: key, msg: value });
          }
        });
    },

    // helper method to load field names defined in fragment graphql definition
    getFieldsFromFragment(includeId) {
      let fieldDefs = fragment.definitions[0].selectionSet.selections;
      let fieldNames = fieldDefs.map(field => { return field.name.value; });

      if (!includeId)
        fieldNames = fieldNames.filter(field => {return field != "id"});

      return fieldNames;
    },
  }
};
</script>
