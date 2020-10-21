<template>
    <!-- v-app must wrap all the components -->
    <v-app :dark="$root.dark">
        <div class="card">
            <table v-if="me" class="table table-bordered mb-0 statistics-table">
                <tr>
                    <td><b>Návštěvy tvých úprav</b></td>
                    <td>za poslední 4 týdny</td>
                    <td title="oproti předchozím 4 týdnům" style="text-decoration:underline dotted">změna</td>
                    <td>od 1. září 2020</td>
                </tr>
                <tr>
                    <td>Krátké návštěvy</td>
                    <td>{{ me.month.visits_short }}</td>
                    <td :class="'text-' + change(me.month.visits_short, me.two_months.visits_short, true)">{{
                        change(me.month.visits_short, me.two_months.visits_short) }}</td>
                    <td>{{ me.from_start.visits_short }}</td>
                </tr>
                <tr>
                    <td>Dlouhé návštěvy</td>
                    <td>{{ me.month.visits_long }}</td>
                    <td :class="'text-' + change(me.month.visits_long, me.two_months.visits_long, true)">{{
                        change(me.month.visits_long, me.two_months.visits_long) }}</td>
                    <td>{{ me.from_start.visits_long }}</td>
                </tr>
                <tr>
                    <td title="kombinace krátkých a dlouhých návštěv (k + 3d)" style="text-decoration:underline dotted">Zpěvníkové návštěvy</td>
                    <td>{{ our(me.month) }}</td>
                    <td :class="'text-' + change(our(me.month), our(me.two_months), true)">{{
                        change(our(me.month), our(me.two_months)) }}</td>
                    <td>{{ our(me.from_start) }}</td>
                </tr>
            </table>
        </div>
        <div class="card">
            <table v-if="top" class="table table-bordered mb-0 statistics-table">
                <tr>
                    <td><b>Nejlepší redaktoři</b> za poslední 4 týdny</td>
                    <td title="kombinace krátkých a dlouhých návštěv (k + 3d)" style="text-decoration:underline dotted">Zpěvníkové návštěvy</td>
                    <td title="oproti předchozím 4 týdnům" style="text-decoration:underline dotted">změna</td>
                </tr>
                <tr v-for="(row, key) in top" :key="row.id">
                    <td>{{ key + 1 }}. <span
                    :style="{textDecoration: [row.id == userId ? 'underline dotted' : 'none']}"
                    :title="[row.id == userId ? 'to jsi ty' : '']">{{ row.name }}</span></td>
                    <td>{{ our(row.month) }}</td>
                    <td :class="'text-' + change(our(row.month), our(row.two_months), true)">{{
                        change(our(row.month), our(row.two_months)) }}</td>
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
    props: ['user-id'],

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
                    userJson.two_months = {visits_short: 0, visits_long: 0};
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
                        user.two_months = userJson.two_months;
                    } else {
                        user.month = {visits_short: 0, visits_long: 0};
                        user.two_months = {visits_short: 0, visits_long: 0};
                    }

                    topUsers[i] = user;
                }

                topUsers.sort((a,b) => (this.our(a.month) > this.our(b.month)) ? -1 : ((this.our(b.month) > this.our(a.month)) ? 1 : 0));
                return topUsers.slice(0, 5);
            }
        }
    },

    methods: {
        change(last, lastTwo, retClass) {
            let number = last - (lastTwo - last);

            if (number > 0) {
                return retClass ? 'success' : '+ ' + number;
            } else if (number == 0) {
                return retClass ? '' : '–';
            } else {
                return retClass ? 'warning' : '− ' + (number * -1);
            }
        },

        our(timeObject) {
            return timeObject.visits_short + 3*timeObject.visits_long;
        }
    }
};
</script>
