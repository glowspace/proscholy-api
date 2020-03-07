<template>
    <div>
        <div>Akordy</div>
        <div class="btn-group m-0 bg-light"
             role="group">
            <a class="btn btn-secondary"
               :class="{'chosen': chordMode === 0}"
               @click="chordMode = 0"><i class="far fa-eye-slash"></i></a>
            <a class="btn btn-secondary"
               :class="{'chosen': chordMode === 2}"
               @click="chordMode = 2"><i class="far fa-eye"></i></a>
        </div>
    </div>
</template>

<script>
    export default {
        props: ["value", "n-chord-modes"],

        computed: {
            chordMode: {
                get() {
                    return this.value;
                },
                set(val) {
                    this.$emit("input", val)
                }
            }
        }
    }
</script>

