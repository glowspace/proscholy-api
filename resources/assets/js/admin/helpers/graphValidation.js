function graphqlErrorsToValidator(validator, graphql_error) {
    let errorFields =
        graphql_error.graphQLErrors[0].extensions.validation;

    // clear the old errors and (add new ones if exist)
    validator.errors.clear();
    for (const [key, value] of Object.entries(errorFields)) {
        let _value = Array.isArray(value) ? value[0] : value;
        validator.errors.add({ field: key, msg: _value });
    }
}

export { graphqlErrorsToValidator }