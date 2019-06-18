const $ = require('jquery');

$('form').submit(function (event) {
    event.preventDefault();
    let id = $(this).data('id');
    let file = $(this).find('input').prop('files')[0];
    let product = $(this).data('product');
    let data = new FormData();
    if(file)
    data.append('file',file);
    if(id)
    data.append('id', id);
    if(product)
    data.append('product',product);
    let url = $(this).data('url');
    console.log(url);
    let img = $(this).parent().siblings('img');
    let input = $(this).find('input');
    $.ajax({
        url: '/lipadmin/'+url+'/changePhoto',
        type: 'POST',
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        success: function (res) {
            console.log(res);
            if(res.hash) {
               img.attr('src', '/uploads/'+url+'/' + res.hash + '?' + new Date().getTime());
               input.val('');
            }
        }
    });
    if(!$(this).siblings('.delete_photo').length)
        $(this).after('<button data-id="'+id+'" style="margin-top: 10px" class="btn btn-danger delete_photo">Удалить</button>');

});

$('.delete_photo').click(function() {
    let id = $(this).data('id');

    $.ajax({
        url: '/lipadmin/'+url+'/deletePhoto',
        type: 'DELETE',
        data: {
            id: id
        },
        success: function (res) {
            $('#photo-'+id).attr('src','/assets/img/noImg.jpg');
        }
    });
    $(this).prev().removeAttr('data-id');
    $(this).remove();
});

