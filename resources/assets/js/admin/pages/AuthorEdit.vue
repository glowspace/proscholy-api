<template>
  <v-app>
    <notifications/>
    <v-container fluid grid-list-xs>
      <v-layout row>
        <v-flex xs12 md6>
          <v-form ref="form">
            <v-text-field
              label="Jméno autora"
              required
              v-model="model.name"
              data-vv-name="input.name"
              :error-messages="errors.collect('input.name')"
            ></v-text-field>
            <v-select :items="type_values" v-model="model.type" label="Typ"></v-select>
            <v-textarea
              name="input-7-4"
              label="Popis autora"
              v-model="model.description"
              data-vv-name="input.description"
              :error-messages="errors.collect('input.description')"
            ></v-textarea>
            <v-btn @click="submit" :disabled="!isDirty">Uložit</v-btn>
            <v-btn @click="show" :disabled="isDirty">Zobrazit ve zpěvníku</v-btn>
          </v-form>
        </v-flex>
        <v-flex xs12 md6 class="edit-description">
          <h5>Seznam autorských písní</h5>
          <v-btn
            v-for="song_lyric in model.song_lyrics"
            v-bind:key="song_lyric.id"
            class="text-none"
            @click="goToAdminPage('song/' + song_lyric.id + '/edit')"
          >{{ song_lyric.name }}</v-btn>

          <p></p>
          <h5>Seznam materiálů</h5>
          <v-btn
            v-for="external in model.externals"
            v-bind:key="external.id"
            class="text-none"
            @click="goToAdminPage('external/' + external.id + '/edit')"
          >{{ external.public_name }}</v-btn>
          <v-btn
            v-for="file in model.files"
            v-bind:key="file.id"
            class="text-none"
            @click="goToAdminPage('file/' + file.id + '/edit')"
          >{{ file.public_name }}</v-btn>
        </v-flex>
      </v-layout>
    </v-container>
  </v-app>
</template>

<script>
import gql, { disableFragmentWarnings } from "graphql-tag";
import fragment from "@/graphql/client/author_fragment.graphql";

const FETCH_MODEL_DATABASE = gql`
  query($id: ID!) {
    model_database: author(id: $id) {
      ...AuthorFillableFragment
      type_string_values
    }
  }
  ${fragment}
`;

const MUTATE_MODEL_DATABASE = gql`
  mutation($input: UpdateAuthorInput!) {
    update_author(input: $input) {
      ...AuthorFillableFragment
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
        name: undefined,
        type: undefined,
        description: undefined,
        song_lyrics: [],
        externals: [],
        files: []
      },
      type_values: []
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
        let author = result.data.model_database;
        // load the requested fields to the vue data.model property
        for (let field of this.getFieldsFromFragment(false)) {
          Vue.set(this.model, field, author[field]);
        }

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
    this.model.id = this.presetId;

    // prevent user to leave the form if dirty
    window.onbeforeunload = e => {
      if (this.isDirty) {
        e.preventDefault();
        e.returnValue = "";
      }
    };
  },

  computed: {
    isDirty() {
      if (!this.model_database) return false;

      for (let field of this.getFieldsFromFragment(this)) {
        if (!_.isEqual(this.model[field], this.model_database[field])) {
          return true;
        }
      }

      return false;
    }
  },

  methods: {
    submit() {
      this.$apollo
        .mutate({
          mutation: MUTATE_MODEL_DATABASE,
          variables: {
            input: {
              id: this.model.id,
              name: this.model.name,
              type: this.model.type,
              description: this.model.description
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
          if (error.graphQLErrors.length == 0) {
            // unknown error happened
            this.$notify({
              title: "Chyba při ukládání",
              text: "Autor nebyl uložen",
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
      let fieldNames = fieldDefs.map(field => {
        if (field.alias) return field.alias.value;
        return field.name.value;
      });

      if (!includeId)
        fieldNames = fieldNames.filter(field => {
          return field != "id";
        });

      return fieldNames;
    },

    async goToAdminPage(url) {
      if (this.isDirty) await this.submit();

      setTimeout(() => {
        // if there has been an error then this does not continue
        if (!this.isDirty) {
          var base_url = document
            .querySelector("#baseUrl")
            .getAttribute("value");
          window.location.href = base_url + "/admin/" + url;
        }
      }, 500);
    },

    show() {
      var base_url = document.querySelector("#baseUrl").getAttribute("value");
      window.location.href = base_url + "/autor/" + this.model.id;
    }
  }
};
</script>
