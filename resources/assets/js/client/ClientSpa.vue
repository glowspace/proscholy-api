<template>
    <div>
        <Navbar/>

        <router-view/>
    </div>
</template>

<script>
    import Navbar from "./layout/Navbar";

    export default {
        name: "ClientSpa",
        components: {Navbar}
    }
</script>

<style scoped>

</style>
