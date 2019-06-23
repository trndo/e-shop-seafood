import $ from 'jquery';

let category = null;
let type = null;
let tags = [];
let sizes = [];
$(document).ready(function () {
   let slug = $('.saveProducts').data('slug');

   $.ajax({
       url: '/lipadmin/receipts/'+slug+'/checkProducts',
       type: 'GET',
       success: function(res) {
           if(!res.empty){
               console.log(res);
               category = res.category;
               type = res.type;
               res.ids.forEach(function (elem) {
                   tags.push(elem);
               });
               $.ajax({
                   type: "GET",
                   url: '/lipadmin/products/getForReceipts',
                   data: {
                       category: category,
                       type: type
                   },
                   success: function (res) {
                       $('.product-list').find('tbody').html(res);
                       $('.second-step').show();
                       $('#tag-container').show();
                       $('.saveProducts').show();
                       $('.product-list').show();
                   }
               })
           }
       }
   })
});

$('.category').click(function () {
    $('.second-step').show();
    $('.product-list').hide();
    category = $(this).data('id');
    clearData();
});

$('.addProd').click(function () {
    type = $(this).data('type');
    clearData();
    $.ajax({
        type: "GET",
        url: '/lipadmin/products/getForReceipts',
        data: {
            category: category,
            type: type
        },
        success: function (res) {
            $('.product-list').find('tbody').html(res);
            $('#tag-container').show();
            $('.saveProducts').show();
            $('.product-list').show();
        }
    })
});

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

function addTag(product) {
    tags.push(product.id);
    if(product.size === undefined)
        $('#tag-container').append(
            '<div class="tag" data-id="'+product.id+'">'+product.name+'<i class="fas fa-times deltag"></i></div>'
        );
    else {
        sizes.push(product.size);
        $('#tag-container').append(
            '<div class="tag" data-id="' + product.id + '">' + product.name + ' ' + product.size + '<i class="fas fa-times deltag"></i></div>'
        );
    }
}

function clearData() {
    $('#tag-container').empty().hide();
    $('.saveProducts').hide();
    $('.product-list').find('tbody').empty();
    tags = [];
    sizes = [];
}
function validateTags(data){
    if(type === 'one') {
        if(tags.length > 0) {
            alert('Внимание! Вы хотите добавить большое 1 продукта в рецепт. Выбирете другой метод добавления - "Добавить продукты с размерностями"');
            return false;
        }
        return  true;
    } else if(type === 'sizes'){
        console.log(tags.indexOf(data.id));
        if (tags.indexOf(data.id) !== -1) {
            alert('Внимание! Такой продукт уже добавлен в рецепт');
            return false;
        }
        if(sizes.indexOf(data.size) !== -1){
            alert('Внимание! Такой размер продукта уже добавлен в рецепт');
            return false;
        }
        if(tags.length === 5) {
            alert('Внимание! Нельзя добавить больше 5 размеров');
            return false;
        }
        return  true;
    }
}

$('.saveProducts').click(function () {
    let receipt = $(this).data('receipt-id');
    let slug = $(this).data('slug');
    $.ajax({
      url: '/lipadmin/receipts/'+slug+'/saveProducts',
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

