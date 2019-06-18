
import 'jquery-ui/themes/base/core.css';
import 'jquery-ui/themes/base/menu.css';
import 'jquery-ui/themes/base/autocomplete.css';
import 'jquery-ui/themes/base/theme.css';
import $ from 'jquery';
import  autocomplete  from 'jquery-ui/ui/widgets/autocomplete';

$(document).ready(function () {

    $('#search').autocomplete({
        source: '/lipadmin/products/search'
    });

});
