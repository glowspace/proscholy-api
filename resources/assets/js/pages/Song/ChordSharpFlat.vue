<template>
    <div>
        <div>Posuvky</div>
        <div class="btn-group m-0 bg-light" role="group">
            <a
                class="btn btn-secondary"
                v-bind:class="{ chosen: !useFlatScale }"
                v-on:click="useFlatScale = false"
                >#</a
            >
            <a
                class="btn btn-secondary"
                v-bind:class="{ chosen: useFlatScale }"
                v-on:click="useFlatScale = true"
                >♭</a
            >
        </div>
    </div>
</template>

<script>
export default {
    props: ['value'],

    computed: {
        useFlatScale: {
            get() {
                return this.value;
            },
            set(val) {
                this.$emit('input', val);
            }
        }
    }
};
</script>
