
import Vue from 'vue';

const getFieldsFromFragment = function (fragment, options = { includeId: true}) {

    if (!fragment) {
        throw new Error("Fragment is not defined.");
    }

    let fieldDefs = fragment.definitions[0].selectionSet.selections;
    let fieldNames = fieldDefs.map(field => {
        if (field.alias) return field.alias.value;
        return field.name.value;
    });

    if (!options.includeId)
        fieldNames = fieldNames.filter(field => {
        return field != "id";
        });

    return fieldNames;
}

export { getFieldsFromFragment }