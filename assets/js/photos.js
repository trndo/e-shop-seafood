const $ = require('jquery');

$('form').submit(function (event) {
    event.preventDefault();
    let id = $(this).attr('data-id');
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
    let img = $(this).parent().siblings('img');
    let input = $(this).find('input');
    let form = $(this);

    $.ajax({
        url: '/lipadmin/'+url+'/changePhoto',
        type: 'POST',
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        success: function (res) {
            if(res.hash) {
               img.attr('src', '/uploads/'+url+'/' + res.hash + '?' + new Date().getTime());
               input.val('');
            }
            if(res.id){
                id = res.id;
                form.attr('data-id',id);
            }

            if(!form.siblings('.delete_photo').length)
                form.after('<button data-id="'+id+'" data-url="'+url+'" style="margin-top: 10px" class="btn btn-danger delete_photo">Удалить</button>');
        }
    });



});

$(document).on('click','.delete_photo',function() {
    let id = $(this).data('id');
    let url = $(this).data('url');
    let img = $(this).parent().prev();
    $.ajax({
        url: '/lipadmin/'+url+'/deletePhoto',
        type: 'DELETE',
        data: {
            id: id
        },
        success: function (res) {
            img.attr('src','/assets/img/noImg.jpg');
        }
    });

    $(this).prev().attr('data-id',null);
    $(this).remove();
});

