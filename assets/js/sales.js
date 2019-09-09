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
            let id = $(this).data('id');
            let type = $(this).data('type');
            tags.push({ type: type, id: id});
        })
   }
});

$('.category').click(function () {
    $('.second-step').show();
    category = $(this).data('id');
    let type = $(this).data('type');
    clearData();
    $.ajax({
        type: "GET",
        url: '/lipadmin/'+type+'/byCategory',
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
            let regex = new RegExp('.*'+item+'.*','i');

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
    let type = $(this).data('type');

    if(validateTags({id: id, name: name, size: size, type: type}))
        addTag({id: id, name: name, size: size, type: type})
});

function addTag(product) {
    tags.push(product);
    console.log(tags);
    if(product.size === undefined)
        $('#tag-container').append(
            '<div class="tag" data-type="'+product.type+'" data-id="'+product.id+'">'+product.name+'<i class="fas fa-times deltag"></i></div>'
        );
    else {
        $('#tag-container').append(
            '<div class="tag" data-type="'+product.type+'" data-id="' + product.id + '">' + product.name + ' ' + product.size + '<i class="fas fa-times deltag"></i></div>'
        );
    }
}

$(document).on('click', '.deltag',function () {
    let id = $(this).parent().data('id');
    let type = $(this).parent().data('type');
    tags.splice(tags.findIndex(elem => elem.id === id && elem.type === type),1);
    $(this).parent().remove();
});

function validateTags(data){
        let mainInfo = $('#main-info');
        if(mainInfo.data('id') === data.id && mainInfo.data('type') === data.type){
            alert('Невозможно добавть товар к себе самому');
            return false;
        }

        if (tags.find(elem => elem.id === data.id && elem.type === data.type)) {
            alert('Внимание! Такой продукт уже добавлен в доп. продажи');
            return false;
        }
        if(tags.length === 3) {
            alert('Внимание! Нельзя добавить больше 3 доп. продаж');
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
            window.location.href = '/lipadmin/'+route+'/'+slug+'/show';
        }
    })
});


