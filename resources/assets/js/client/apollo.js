import {createHttpLink} from "apollo-link-http";
import {InMemoryCache} from "apollo-cache-inmemory";
import {ApolloClient} from "apollo-client";
import VueApollo from "vue-apollo";

var base_url = document.querySelector('#baseUrl').getAttribute('value');

// HTTP connection to the API
const httpLink = createHttpLink({
    // You should use an absolute URL here
    uri: base_url + '/graphql',
});

const cache = new InMemoryCache();

// Apollo client
const apolloClient = new ApolloClient({
    link: httpLink,
    cache,
});

export default new VueApollo({
    defaultClient: apolloClient,
});
