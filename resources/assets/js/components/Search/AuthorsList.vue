<template>
    <table class="table">
        <template v-if="authors && authors.length && !$apollo.loading">
            <tr v-for="author in authors"
                v-bind:key="author.id">
                <td>
                    <!-- <a href="{{route('client.author',$author)}}">{{$author->getSearchTitle()}}</a>
                    - {{$author->getSearchText()}} -->
                    <a :href="author.public_url">{{ author.name }} - {{ author.type_string }}</a>
                </td>
            </tr>
        </template>
        <tr v-else>
            <td v-if="$apollo.loading">
                <i>Načítání</i>
            </td>
            <td v-else>
                <i>Žádný autor s tímto jménem nebyl nalezen.</i>
            </td>
        </tr>
        <!-- @forelse($authors as $author)
            <tr>
                <td>
                    <a href="{{route('client.author',$author)}}">{{$author->getSearchTitle()}}</a>
                    - {{$author->getSearchText()}}
                </td>
            </tr>
        @empty
            <tr>
                <td>
                    <i>Žádný autor nebyl nalezen.</i>
                </td>
            </tr>
        @endforelse -->
    </table>
</template>

<script>
import gql from 'graphql-tag';

// Query
const fetch_items = gql`
    query ($search_str: String) {
        authors(search_string: $search_str, order_abc: true) {
            id,
            name,
            public_url,
            type_string
        }
    }`;
export default {
    props: ['search-string'],

    // GraphQL client
    apollo: {
        authors: {
            query: fetch_items,
            variables() {
                return {
                    search_str: this.searchString
                }
            },
            // debounce waits 200ms for query refetching
            debounce: 200
        }
    }
}
</script>
