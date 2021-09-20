window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.bootstrap = require('bootstrap')

window.slugify = require('slugify')
window.slugify.extend({
    'υ': 'u',
    'ύ': 'u',
    'Υ': 'u',
    'Ύ': 'U',
    'θ': 'th',
    'Θ': 'TH',
    'ξ': 'ks',
    'Ξ': 'KS',
    'η': 'i'
});

window.slugifyLower = function (string) {
    return slugify(string, {lower: true})
}