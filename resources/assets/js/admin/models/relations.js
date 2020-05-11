/**
 * Data tranformer method, takes the input data in following format:
 * [
 *  {id: 1, data: "updated data for old model"},
 *  {data: "data for new model"}
 * ]
 *
 * @param {Array} relationModels
 * @param {Object} options
 * @param {Boolean} options.disableCreate - Disable creating new models
 * @returns {Object} Input object for Lighthouse's mutation
 *
 */
const belongsToManyMutator = function(
    relationModels,
    options = {
        disableCreate: false
    }
) {
    let obj = {
        sync: relationModels.filter(x => x.hasOwnProperty('id')).map(x => x.id)
    };

    if (!options.disableCreate) {
        obj.create = relationModels.filter(x => !x.hasOwnProperty('id'));
    }

    return obj;
};

/**
 * Data transformer method, takes the input data in following format:
 *
 * @param {Object|null} relationModel
 * @returns {Object} Input object for Lighthouse's mutation
 */
const belongsToMutator = function(relationModel) {
    let obj = {};

    if (relationModel) {
        obj.update = {
            id: relationModel.id
        };
    } else {
        obj.disconnect = true;
    }

    return obj;
};

export { belongsToManyMutator, belongsToMutator };
