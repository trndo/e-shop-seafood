const $ = require('jquery');

$(document).ready(function () {
    let url = $('.live-search-form').data('url');

    $('#searchItem').keyup(function (e) {
        let item = $(this).val();
        $('.liveSearch').empty();
        if(item !== '') {
            $.ajax({
                url: '/lipadmin/'+url+'/live_search',
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

