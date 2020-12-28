<template>
    <span v-if="embedded" title="dosah od 1. září 2020">{{
        me ? our(me.from_start).toLocaleString() : '…' }}</span>
    <v-app v-else :dark="$root.dark">
        <div class="card">
            <table class="table mb-0 statistics-table">
                <tr>
                    <td><b>Nejlepší redaktoři</b></td>
                    <td>dosah za poslední 4&nbsp;týdny</td>
                    <td title="oproti předchozím 4 týdnům" style="text-decoration:underline dotted">změna</td>
                </tr>
                <tr v-for="(row, key) in top" :key="row.id" v-if="top && (key < 5 || row.id == userId)">
                    <td>{{ key + 1 }}. <span
                    :style="{textDecoration: [row.id == userId ? 'underline dotted' : 'none']}"
                    :title="[row.id == userId ? 'to jsi ty' : '']">{{ row.name }}</span><i v-if="!key" class="fas fa-fish pl-2" style="color:gold;-webkit-text-stroke:black 1px"></i></td>
                    <td>{{ our(row.month).toLocaleString() }}</td>
                    <td :class="'text-' + change(our(row.month), our(row.prev_month), true)">{{
                        change(our(row.month), our(row.prev_month)) }}</td>
                </tr>
            </table>
        </div>
    </v-app>
</template>

<script>
import gql from 'graphql-tag';

const fetch_items = gql`
    query FetchStats {
        users {
            id
            name
            stats_json
        }
    }
`;

export default {
    props: ['user-id', 'embedded'],

    apollo: {
        users: {
            query: fetch_items
        }
    },

    computed: {
        me() {
            if (this.users) {
                let user = this.users.find(obj => obj.id === this.userId);
                let userJson = JSON.parse(JSON.parse(user.stats_json));

                if (!userJson) {
                    userJson = {};
                    userJson.month = {visits_short: 0, visits_long: 0};
                    userJson.prev_month = {visits_short: 0, visits_long: 0};
                    userJson.from_start = {visits_short: 0, visits_long: 0};
                }

                return userJson;
            }
        },

        top() {
            if (this.users) {
                let topUsers = [...this.users];

                for (let i = 0; i < topUsers.length; i++) {
                    let user = topUsers[i];
                    let userJson = JSON.parse(JSON.parse(user.stats_json));

                    if (userJson) {
                        user.month = userJson.month;
                        user.prev_month = userJson.prev_month;
                    } else {
                        user.month = {visits_short: 0, visits_long: 0};
                        user.prev_month = {visits_short: 0, visits_long: 0};
                    }

                    topUsers[i] = user;
                }

                topUsers.sort((a,b) => (this.our(a.month) > this.our(b.month)) ? -1 : ((this.our(b.month) > this.our(a.month)) ? 1 : 0));
                return topUsers;
            }
        }
    },

    methods: {
        change(last, prev, retClass) {
            let number = last - prev;

            if (number > 0) {
                return retClass ? 'success' : '+ ' + number.toLocaleString();
            } else if (number == 0) {
                return retClass ? '' : '–';
            } else {
                return retClass ? 'warning' : '− ' + (number * -1).toLocaleString();
            }
        },

        our(timeObject) {
            return timeObject ? (timeObject.visits_short + 3*timeObject.visits_long) : 0;
        }
    }
};
</script>
