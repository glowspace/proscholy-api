<template>
    <div>
        <div>Transpozice</div>
        <div class="btn-group m-0" role="group">
            <a class="btn btn-secondary" v-on:click="transpose(-1)">-</a>
            <a
                class="btn btn-secondary bg-light transpose-window"
                v-on:click="reset()"
                >{{ transposition }}</a
            >
            <a class="btn btn-secondary" v-on:click="transpose(1)">+</a>
        </div>
    </div>
</template>

<script>
export default {
    props: ['value'],

    data() {
        return {
            transposition: 0
        };
    },

    methods: {
        transpose: function(val) {
            this.transposition = (this.transposition + val) % 12;
            this.$emit('input', this.transposition);
        },

        reset() {
            this.transposition = 0;
            this.$emit('input', this.transposition);
        }
    }
};
</script>
