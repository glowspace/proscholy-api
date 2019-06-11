<template>
    <div>
        <div>Posuvky</div>
        <div class="btn-group m-0 bg-light" role="group">
            <a class="btn btn-secondary"
            v-bind:class="{'chosen': !useFlatScale}" v-on:click="updateValue(false)">#</a>
            <a class="btn btn-secondary"
            v-bind:class="{'chosen': useFlatScale}" v-on:click="updateValue(true)">♭</a>
        </div>
    </div>
</template>

<script>
export default {
    props: ["value"],

    data() {
        return {
            useFlatScale: false
        }
    },

    methods: {
        updateValue(val) {
            this.useFlatScale = val;

            this.$emit("input", val);
        }
    }
}
</script>

