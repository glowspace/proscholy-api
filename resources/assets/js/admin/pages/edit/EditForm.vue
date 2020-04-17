<script>
import { getFieldsFromFragment } from "Admin/models/manipulation";

export default {
  props: ["preset-id"],

  computed: {
    isDirty() {
      if (!this.model_database) return false;

      for (let field of getFieldsFromFragment(this.fragment)) {
        if (!_.isEqual(this.model[field], this.model_database[field])) {
          return true;
        }
      }

      return false;
    }
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

    loadModelDataFromResult(result) {
      // load the requested fields to the vue data.model property
      for (let field of getFieldsFromFragment(this.fragment, { includeId: false })) {
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
    }
  }
};
</script>