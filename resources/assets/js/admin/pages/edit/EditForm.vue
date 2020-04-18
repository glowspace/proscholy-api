<script>
import { getFieldsFromFragment } from "Admin/models/manipulation";

export default {
  props: ["preset-id"],

  computed: {
    isDirty() {
      if (this.is_deleted) return false;
      if (!this.model_database) return false;
    //   if (!this.model.url) return true;

      for (let field of getFieldsFromFragment(this.fragment)) {
        if (!_.isEqual(this.model[field], this.model_database[field])) {
          return true;
        }
      }

      return false;
    }
  },

  mounted() {
    if (!this.fragment) {
        throw new Error("Edit form's data.fragment is not defined!");
    }
    if (!this.model) {
        throw new Error("Edit form's data.model is not defined!");
    }

    this.model.id = this.presetId;

    // prevent user to leave the form if dirty
    window.onbeforeunload = e => {
      if (this.isDirty) {
        e.preventDefault();
        e.returnValue = "";
      }
    };
  },

  $_veeValidate: {
    validator: "new"
  },

  methods: {
    async goToPage(url, save = true) {
      if (this.isDirty && save) await this.submit();

      setTimeout(() => {
        if (!this.isDirty && save) {
          var base_url = document
            .querySelector("#baseUrl")
            .getAttribute("value");
          window.location.href = base_url + "/" + url;
        }
      }, 500);
    },

    goToAdminPage(url, save = true) {
      this.goToPage("/admin/" + url, save);
    },

    handleValidationErrors(error) {
      let errorFields = error.graphQLErrors[0].extensions.validation;

      // clear the old errors and (add new ones if exist)
      this.$validator.errors.clear();
      for (const [key, value] of Object.entries(errorFields)) {
        this.$validator.errors.add({ field: key, msg: value });
      }
    },

    reset() {
      for (let field of getFieldsFromFragment(this.fragment, {
        includeId: false
      })) {
        let clone = _.cloneDeep(this.model_database[field]);
        Vue.set(this.model, field, clone);
      }
    },

    loadModelDataFromResult(result) {
      console.log(getFieldsFromFragment(this.fragment));

      // load the requested fields to the vue data.model property
      for (let field of getFieldsFromFragment(this.fragment, {
        includeId: false
      })) {
        Vue.set(
          this.model,
          field,
          _.cloneDeep(result.data.model_database[field])
        );
      }
    },

    loadEnumJsonFromResult(result, enumName, vueEnumModel) {
      const data = JSON.parse(result.data.model_database[enumName]);
      console.log(data);

      for (const [key, value] of Object.entries(data)) {
        let key_parsed = parseInt(key);
        if (isNaN(key_parsed)) {
          // means we are dealing with an associative array (using strings as keys)
          key_parsed = key;
        }

        vueEnumModel.push({ value: key_parsed, text: value });
      }
    },

    prepareDataForMutation(mutators) {
      mutators_mock = {
        authors: this.makeBelongsToManyMutator({ sync: true, create: true }),
        song_lyrics: this.makeBelogsToMutator()
      }

      let result = {};

      // load fragment data
      const fields = this.getFieldsFromFragment(this.fragment);
      const fieldsToMutate = Object.keys(mutators_mock);

      for (const field of fields) {
        if (field in fieldsToMutate) {
          const mutatorFunc = mutators_mock[field];
          result[field] = mutatorFunc(this.model[field]);
        } else {
          result[field] = this.model[field];
        }
      }

      return result;
    },

    makeBelongsToManyMutator(options = {
      sync: true,
      create: true
    }) {
      return function (relationData) {
        let obj = {};

        if (options.sync) {
          obj.sync = relationData.filter(x => x.hasOwnProperty("id")).map(x => x.id)
        }
        if (options.create) {
          obj.create = relationData.filter(x => !x.hasOwnProperty("id")),
        }

        return obj;
      }
    },

    makeBelogsToMutator() {
      return function(relationData) {
        let obj = {};
  
        if (model) {
          obj.update = {
            id: model.id
          }
        } else {
          obj.disconnect = true;
        }
        
        return obj;
      }
    }
  }
};
</script>