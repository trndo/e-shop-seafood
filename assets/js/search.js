const $ = require('jquery');

$(document).ready(function () {
    $('#searchItem').keyup(function (e) {
        let item = $(this).val();
        $('.liveSearch').empty();
        if(item !== '') {
            $.ajax({
                url: '/lipadmin/supply/search',
                method: 'GET',
                data: {q: item},
                success: function (res) {
                    console.log(res);
                    $('.liveSearch').html(res);
                }
            })
        }
        else{
            $('.liveSearch').html('');
        }
    })
});

