import $ from "jquery";

let category = null;
let type = null;
let tags = [];

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

    if(type === 'one') {
        if(validateTags({id: id, name: name}))
            addTag({id: id, name: name})
    } else if(type === 'sizes') {
        if(validateTags({id: id, name: name, size: size}))
            addTag({id: id, name: name, size: size})
    }
});


$(document).on('click', '.deltag',function () {
    let id = $(this).data('id');
    tags.splice(tags.indexOf(id),1);
    $(this).parent().remove();
});

function validateTags(data){
        if (tags.indexOf(data.id) !== -1) {
            alert('Внимание! Такой продукт уже добавлен в рецепт');
            return false;
        }
        if(tags.length === 3) {
            alert('Внимание! Нельзя добавить больше 3 продуктов');
            return false;
        }
        return  true;
}

$('.saveProducts').click(function () {
    let receipt = $(this).data('receipt-id');
    let slug = $(this).data('slug');
    $.ajax({
        url: '/lipadmin/receipts/'+slug+'/saveSales',
        type: 'POST',
        data: {
            products: tags,
            id: receipt
        },
        success: function (res) {
            window.location.href = '/lipadmin/receipts/'+slug+'/show';
        }
    })
});


