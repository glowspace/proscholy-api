import removeDiacritics from './removeDiacritics';

const stringToSearchable = str =>
    removeDiacritics(str ?? '')
        .replace(',', '')
        .replace('(', '')
        .replace(')', '')
        .toLowerCase();

const getSongLyricFullName = sl => {
    var name = sl.name;
    if (sl.secondary_name_1 && sl.secondary_name_1.length) {
        name += ' (' + sl.secondary_name_1;
        if (sl.secondary_name_2 && sl.secondary_name_2.length) {
            name += ', ' + sl.secondary_name_2;
        }
        name += ')';
    }
    return name;
};

export { stringToSearchable, getSongLyricFullName };
