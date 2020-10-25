<template>
    <v-layout>
        <v-flex>
            {{ external.url }}<br />
            <span style="color: gray">{{
                external.authors.map(a => a.name).join(', ')
            }}</span>
        </v-flex>
        <v-flex xs2>
            <v-btn color="info" outline>Upravit</v-btn>
        </v-flex>
        <v-flex xs2>
            <v-btn color="error" outline>Odstranit</v-btn>
        </v-flex>
    </v-layout>
</template>

<script>
export default {
    props: ['external']
};
</script>
