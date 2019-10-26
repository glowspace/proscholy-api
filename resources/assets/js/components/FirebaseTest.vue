<template>
<ul>
    <li v-for="user in users" v-bind:key="user.id">{{ user }} - songbooks: 
        <span v-for="songbook in user.songbooks" v-bind:key="songbook.name"></span>
    </li>
</ul>
</template>

<script>
import { db } from 'Public/helpers/firebasedb'

export default {
    data() {
        return {
            users:[]
        }
    },

    firestore: {
        users: db.collection('users')
    }
}
</script>