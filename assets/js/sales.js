import $ from "jquery";

let category = null;
let type = null;
let tags = [];
$(document).ready(function () {
   let existTags = $('#tag-container').children();
   if(existTags.length){
       $('#tag-container').show();
       $('.saveProducts').show();
        existTags.each(function () {
            tags.push($(this).data('id'));
        })
   }
});

$('.category').click(function () {
    $('.second-step').show();
    category = $(this).data('id');
    clearData();
    $.ajax({
        type: "GET",
        url: '/lipadmin/products/byCategory',
        data: {
            category: category
        },
        success: function (res) {
            $('.product-list').find('tbody').html(res);
            $('#tag-container').show();
            $('.saveProducts').show();
            $('.product-list').show();
        }
    })
});


function clearData() {
    $('.product-list').find('tbody').empty();
}

$('#searchItem').keyup(function (e) {
    let item = $(this).val();
    if(item !== '') {
        $('.forSearch').each(function () {
            let name = $(this).data('search');
            let regex = new RegExp('.*'+item+'.*');

            if(!name.match(regex)) {
                $(this).hide();
            }
            else
                $(this).show();
        })
    }
    else{
        $('.forSearch').each(function () {
            $(this).show();
        })
    }
});

$(document).on('click', '.addTag',function () {
    let id = $(this).data('id');
    let name = $(this).data('name');
    let size = $(this).data('size');

    if(validateTags({id: id, name: name, size: size}))
        addTag({id: id, name: name, size: size})
});

function addTag(product) {
    tags.push(product.id);
    console.log(tags);
    if(product.size === undefined)
        $('#tag-container').append(
            '<div class="tag" data-id="'+product.id+'">'+product.name+'<i class="fas fa-times deltag"></i></div>'
        );
    else {
        $('#tag-container').append(
            '<div class="tag" data-id="' + product.id + '">' + product.name + ' ' + product.size + '<i class="fas fa-times deltag"></i></div>'
        );
    }
}


$(document).on('click', '.deltag',function () {
    let id = $(this).parent().data('id');
    tags.splice(tags.indexOf(id),1);
    $(this).parent().remove();
});

function validateTags(data){
        if (tags.indexOf(data.id) !== -1) {
            alert('Внимание! Такой продукт уже добавлен в доп. продажи');
            return false;
        }
        if(tags.length === 3) {
            alert('Внимание! Нельзя добавить больше 3 продуктов');
            return false;
        }
        return  true;
}

$('.saveProducts').click(function () {
    let slug = $(this).data('slug');
    let route = $(this).data('route');
    $.ajax({
        url: '/lipadmin/'+route+'/'+slug+'/saveSales',
        type: 'POST',
        data: {
            products: tags,
        },
        success: function (res) {
            window.location.href = '/lipadmin/receipts/'+slug+'/show';
        }
    })
});


