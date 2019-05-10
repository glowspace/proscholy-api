<template>
  <v-app>
    <notifications group="admin"/>
    <v-container grid-list-xs>
      <v-layout row>
        <v-flex xs12 md6>
          <v-form ref="form">
            <v-text-field
              label="Jméno autora"
              required
              v-model="name"
              data-vv-name="name"
              :error-messages="errors.collect('name')"
            ></v-text-field>
            <v-select :items="type_values" v-model="type" label="Typ"></v-select>
            <v-textarea name="input-7-4" label="Popis autora" v-model="description" 
                data-vv-name="description"
                :error-messages="errors.collect('description')"></v-textarea>

            <v-btn @click="submit">Uložit</v-btn>
            <v-btn @click="move(-1)">Předchozí uživatel</v-btn>
            <v-btn @click="move(1)">Další uživatel</v-btn>
          </v-form>
        </v-flex>
        <v-flex xs12 md6></v-flex>
      </v-layout>
    </v-container>
  </v-app>
</template>

<script>
import gql from "graphql-tag";

const fetch_item = gql`
  query($id: ID!) {
    author(id: $id) {
      id
      name
      type
      type_string_values
      description
    }
  }
`;

const update_item = gql`
  mutation($id: ID!, $name: String, $description: String, $type: Int!) {
    update_author(
      id: $id
      name: $name
      description: $description
      type: $type
    ) {
      id
    }
  }
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
      err: "",

      dictionary: {
        attributes: {
          email: "E-mail Address"
        },
        custom: {
          name: {
            required: () => "Name can not be empty",
          },
        }
      }
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
        console.log(author);
        // one-way copy the data
        this.description = author.description;
        this.name = author.name;
        this.type = author.type;
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
    this.$validator.localize('en', this.dictionary)
  },

  computed: {},

  methods: {
    submit() {

      this.$apollo
        .mutate({
          mutation: update_item,
          variables: {
            id: this.id,
            name: this.name,
            description: this.description,
            type: this.type,
          }
          // refetchQueries: [{
          //     query: fetch_item
          // }]
        })
        .then(result => {
            this.$validator.errors.clear();
            console.log(result);
            this.$notify({
                group: "admin",
                title: "Úspěšně uloženo :)",
                text: "Autor byl úspěšně uložen",
                type: "success"
            });
        })
        .catch(error => {
            this.$validator.errors.clear();

            if (error.graphQLErrors.count() == 0) {
                // unknown error happened
                this.$notify({
                    group: "admin",
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

    move(diff) {
      this.id = Number(this.id) + diff;
    }
  }
};
</script>
