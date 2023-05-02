<template>
    <span>
        <input
            type="hidden"
            name="g-recaptcha-response"
            :value="token"
            v-if="token"
        />
    </span>
</template>

<script>
import { VueReCaptcha } from 'vue-recaptcha-v3';
import Vue from 'vue';

export default {
    props: ['site-key'],

    data() {
        return {
            token: null
        };
    },

    mounted() {
        Vue.use(VueReCaptcha, { siteKey: this.siteKey });
        this.$recaptchaLoaded().then(this.initRecaptcha);
    },

    methods: {
        initRecaptcha() {
            this.$recaptcha('login').then(token => {
                this.token = token;
            });
        }
    }
};
</script>
