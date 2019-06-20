<template>
    <v-text-field
        type="number"
        append-outer-icon="add" @click:append-outer="increment" 
        prepend-icon="remove" @click:prepend="decrement"
        :label="label"
        v-model="number"
        :data-vv-name="vvName"
        :error-messages="errors.collect(vvName)"
    ></v-text-field>
</template>

<script>
export default {
    props: ['value', 'min-value', 'max-value', 'vv-name', 'label'],

    computed: {
        number: {
            get() {
                return this.value;
            },
            set(val) {
                this.$emit("input", val);
            }
        }
    },

    methods: {
        increment() {
            if (this.number + 1 <= this.maxValue) {
                this.number += 1;
            }
        },

        decrement() {
            if (this.number - 1 >= this.minValue) {
                this.number -= 1;
            }
        }
    }
}
</script>
