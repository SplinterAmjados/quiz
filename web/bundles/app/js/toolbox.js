/**
 * Created by anouira on 28/10/2016.
 */


/**
 *
 * **** Datatables *****
 */
var initSimpleDataTable = null;

$(function() {
    var dataTableDefaultLanguage = {
        "sProcessing":     "Traitement en cours...",
        "sSearch":         "Rechercher&nbsp;:",
        "sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
        "sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
        "sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
        "sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
        "sInfoPostFix":    "",
        "sLoadingRecords": "Chargement en cours...",
        "sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
        "sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau",
        "oPaginate": {
            "sFirst":      "Premier",
            "sPrevious":   "Pr&eacute;c&eacute;dent",
            "sNext":       "Suivant",
            "sLast":       "Dernier"
        },
        "oAria": {
            "sSortAscending":  ": activer pour trier la colonne par ordre croissant",
            "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
        }
    }

    initSimpleDataTable = function (selector, options) {
        if (typeof options == 'undefined') {
            options = {};
        }
        var defaultOptions = {
            responsive: true,
            language: dataTableDefaultLanguage,
            pageLength: 10
        };
        $.extend(defaultOptions, options);
        return $(selector).DataTable(defaultOptions);
    }
});

/**
 *
 *  **** AJAX COMMON CALL ***
 */
var ajaxCall = function (options, success, error, complete) {
    var internalErrorMessage = 'Erreur Interne';
    var defaultOptions = {
        method: 'GET',
        dataType : 'JSON',
        success : success
    };
    $.extend(defaultOptions, options);

    if (error) {
        defaultOptions.error = function (jqXHR, textStatus, errorThrown) {
            error(jqXHR, textStatus, errorThrown)
        }
    }

    if (complete) {
        defaultOptions.complete = complete
    }
    return $.ajax(defaultOptions);
};

/**
 * *** Array functions
 */

function serializeArrayToObjectByKey(tab, key) {
    var result = {};
    $.each(tab, function (indice, value) {
        if (value.hasOwnProperty(key) && value.hasOwnProperty('value')) {
            result[value[key]] = value.value;
        }
    });
    return result;
}

function searchInArrayByKeyValue(tab, key, value) {
    var i;
    for (i in tab) {
        var p = tab[i];
        if (p.hasOwnProperty(key)) {
            if (p[key].toString() == value.toString()) {
                return p;
            }
        }
    }
    return null;
}

/**
 * **** String functions
 */
function floatToString(value, n) {

    if (typeof n == 'undefined') {
        n = 2;
    }

    if (value == null) return '';

    value = parseFloat(value);

    if (n == 0) {
        return Math.trunc(value);
    }

    var str = value.toFixed(2);
    str = str.toString().replace('.', ',');

    if (str.indexOf(',') == -1) {
        str = str + ',00';
    }

    while (str.substr(str.indexOf(',') + 1).length < n) {
        str = str + '0';
    }


    if (str.substr(str.indexOf(',') + 1).length > n) {
        str = str.substr(0, str.indexOf(',') + n + 1)
    }

    return str;
}