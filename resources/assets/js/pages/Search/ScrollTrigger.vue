<template>
    <span ref="trigger"></span>
</template>

<script>
// see https://www.netguru.com/codestories/infinite-scroll-with-vue.js-and-intersection-observer

export default {
    props: {
        opts: {
            type: Object,
            default: () => ({
                root: null,
                threshold: '0'
            })
        },
        enabled: {
            type: Boolean,
            default: true
        }
    },

    data() {
        return {
            observer: null
        };
    },

    mounted() {
        this.observer = new IntersectionObserver(entries => {
            this.handleIntersect(entries[0]);
        }, this.opts);

        this.observer.observe(this.$refs.trigger);
    },

    destroyed() {
        this.observer.disconnect();
    },

    methods: {
        handleIntersect(entry) {
            if (this.enabled && entry.isIntersecting)
                this.$emit('triggerIntersected');
        }
    }
};
</script>
