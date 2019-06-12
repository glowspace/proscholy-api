<template>
    <div>
        <div>Akordy</div>
        <div class="btn-group m-0 bg-light" role="group">
            <a class="btn btn-secondary" :class="{'chosen': chordMode == 0}"
            @click="chordMode = 0"><i class="far fa-eye-slash"></i></a>
            <a class="btn btn-secondary" :class="{'chosen': chordMode == 1}"
            @click="chordMode = 1"><i class="far fa-eye"></i></a>
            <a class="btn btn-secondary" v-if="nChordModes > 2" :class="{'chosen': chordMode == 2}"
            @click="chordMode = 2"><i class="fas fa-eye"></i></a>
        </div>
    </div>
</template>

<script>
import { store } from "./store.js";

export default {
    props: ["value", "n-chord-modes"],

    data() {
        return {
            internalValue: 0
        }
    },

    computed: {
        chordMode: {
            get() {
                return this.value;
            },
            set(val) {
                this.internalValue = val;
                this.$emit("input", val);
            }
        }
    },

    methods: {
    }
}
</script>

