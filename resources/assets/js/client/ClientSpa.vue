<template>
    <div>
        <navbar/>

        <router-view></router-view>
    </div>
</template>

<script>
    import Navbar from "./layout/Navbar";

    /**
     * Client web application root component.
     *
     * It wraps menu and router view.
     */
    export default {
        name: "ClientSpa",

        components: {Navbar}
    }
</script>

<style scoped>

</style>
