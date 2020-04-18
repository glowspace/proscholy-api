<script>

export default {
  props: ["preset-id"],

  computed: {
    isDirty() {
      if (this.is_deleted) return false;
      if (!this.model_database) return false;
      //   if (!this.model.url) return true;

      for (let field of this._getFieldsFromFragment(this.fragment)) {
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
      for (let field of this._getFieldsFromFragment(this.fragment, {
        includeId: false
      })) {
        let clone = _.cloneDeep(this.model_database[field]);
        Vue.set(this.model, field, clone);
      }
    },

    loadModelDataFromResult(result) {
      console.log(this._getFieldsFromFragment(this.fragment));

      // load the requested fields to the vue data.model property
      for (let field of this._getFieldsFromFragment(this.fragment, {
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

    _getFieldsFromFragment(fragment, options = { includeId: true }) {
      if (!fragment) {
        throw new Error("Fragment is not defined.");
      }

      let fieldDefs = fragment.definitions[0].selectionSet.selections;
      let fieldNames = fieldDefs.map(field => {
        if (field.alias) return field.alias.value;
        return field.name.value;
      });

      if (!options.includeId)
        fieldNames = fieldNames.filter(field => {
          return field != "id";
        });

      return fieldNames;
    }
  }
};
</script>