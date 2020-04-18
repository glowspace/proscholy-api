
const belongsToManyMutator = function(
    relationModels,
    options = {
        disableCreate: false
    }
) {
    let obj = {
        sync: relationModels.filter(x => x.hasOwnProperty("id")).map(x => x.id)
    };

    if (!options.disableCreate) {
        obj.create = relationModels.filter(x => !x.hasOwnProperty("id"));
    }

    return obj;
};

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

export {
    belongsToManyMutator,
    belongsToMutator
};
