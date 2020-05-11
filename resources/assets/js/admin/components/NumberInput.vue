<template>
    <v-text-field
        type="number"
        class="v-number-input"
        append-outer-icon="add"
        @click:append-outer="increment"
        prepend-icon="remove"
        @click:prepend="decrement"
        :label="label"
        v-model="number"
        :data-vv-name="vvName"
        :error-messages="errors.collect(vvName)"
    ></v-text-field>
</template>

<script>
export default {
    props: {
        value: {
            type: Number
        },
        minValue: {
            type: Number,
            default: Number.NEGATIVE_INFINITY
        },
        maxValue: {
            type: Number,
            default: Number.POSITIVE_INFINITY
        },
        vvName: {
            type: String
        },
        label: {
            type: String
        },
        step: {
            type: Number,
            default: 1
        }
    },

    computed: {
        number: {
            get() {
                return this.value;
            },
            set(val) {
                if (val <= this.maxValue && val >= this.minValue)
                    this.$emit("input", parseInt(val));
            }
        }
    },

    methods: {
        increment() {
            this.number += this.step;
        },

        decrement() {
            this.number -= this.step;
        }
    }
};
</script>

<style lang="scss">
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    /* display: none; <- Crashes Chrome on hover */
    -webkit-appearance: none;
    margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
}

input[type="number"] {
    -moz-appearance: textfield; /* Firefox */
}
</style>
