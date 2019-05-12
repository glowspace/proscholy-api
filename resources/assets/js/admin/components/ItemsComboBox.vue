<template>
  <v-combobox
    v-model="internalValue"
    :filter="filter"
    :hide-no-data="!search"
    :items="items"
    :search-input.sync="search"
    item-text="name"
    hide-selected
    :label="label"
    :multiple="multiple"
    small-chips
  >
    <template v-slot:no-data v-if="enableCustom">
      <v-list-tile>
        <span class="subheading">Create</span>
        <v-chip :color="`green lighten-3`" label small>{{ search }}</v-chip>
      </v-list-tile>
    </template>
    <template v-slot:selection="{ item, parent, selected }">
      <v-chip
        v-if="item === Object(item)"
        :color="getColor(item)"
        :selected="selected"
        label
        small
      >
        <span class="pr-2">{{ item.name }}</span>
        <v-icon small @click="parent.selectItem(item)">close</v-icon>
      </v-chip>
    </template>
    <template v-slot:item="{ index, item }">
      <v-list-tile-content>
        <!-- <template v-if="editing">
            <v-text-field
                v-if="editing === item"
                v-model="editing.name"
                autofocus
                flat
                background-color="transparent"
                hide-details
                solo
                @keyup.enter="edit(index, item)"
                ></v-text-field>
        </template> -->
        <v-chip v-if="editing !== item" :color="getColor(item)" dark label small>{{ item.name }}</v-chip>
      </v-list-tile-content>
      <v-spacer></v-spacer>
      <!-- <v-list-tile-action @click.stop>
        <v-btn icon @click.stop.prevent="edit(index, item)">
          <v-icon>{{ editing !== item ? 'edit' : 'check' }}</v-icon>
        </v-btn>
      </v-list-tile-action> -->
    </template>
  </v-combobox>
</template>

<script>
export default {
  // todo: enable-custom set to false not working for multiple entry
  props: ["p-items", "value", "label", "create-label", "multiple", "enable-custom"],

  data: () => ({
    colors: ["green", "purple", "indigo", "cyan", "teal", "orange"],
    editing: null,
    index: -1,
    search: null,
    items: [{ header: "" }]
  }),

  computed: {
    internalValue: {
      get() {
        return this.value;
      },
      set(val) {
        this.$emit("input", val);
      }
    }
  },

  mounted(){
    this.items[0].header = this.createLabel;
  },

  watch: {
    internalValue(val, prev) {
      if (!val) return;
      if(!Array.isArray(val)) return;

      if (val.length === prev.length) return;

      // fix when the search string remained after selecting an item
      this.search = null;

      this.internalValue = val.map(v => {
        if (typeof v === "string") {
          v = {
            name: v
          };

          this.items.push(v);
        }

        return v;
      });
    },

    pItems(val, prev) {
        console.log("updated " + this.pItems.length);
        this.items = this.items.concat(this.pItems);
    }
  },

  methods: {
    edit(index, item) {
      if (!this.editing) {
        this.editing = item;
        this.index = index;
      } else {
        this.editing = null;
        this.index = -1;
      }
    },
    filter(item, queryText, itemText) {
      if (item.header) return false;

      const hasValue = val => (val != null ? val : "");

      const text = hasValue(itemText);
      const query = hasValue(queryText);

      return (
        text
          .toString()
          .toLowerCase()
          .indexOf(query.toString().toLowerCase()) > -1
      );
    },

    getColor(item){
        if (item.id) {
            return `blue lighten-3`;
        } else {
            return `green lighten-3`;
        }
    }
  }
};
</script>