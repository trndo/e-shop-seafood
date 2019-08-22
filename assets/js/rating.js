import 'jquery-ui/themes/base/core.css';
import 'jquery-ui/themes/base/theme.css';
import 'jquery-ui/themes/base/sortable.css';
const $ = require('jquery');
import 'jquery-ui/ui/core';
import 'jquery-ui/ui/widgets/sortable.js';
import 'jquery-ui-touch-punch'


$(document).ready(function () {


    $('#sortable').sortable({
        containment: "parent",
        cursor: "move",
        update: function (event,ui) {
            $('#sortable' ).draggable;
            $('#sortable').sortable("refreshPositions");
            updateIndex();
            console.log( $('#sortable').sortable("toArray",{"attribute":"data-id"}));
            saveRating($('#sortable').sortable("toArray",{"attribute":"data-id"}));
        },
        remove: function () {
            console.log('removed');
        }
    });

    $('.searchItem').keyup(function (e) {
        let url = $(this).parents('.live-search-form').data('url');
        let item = $(this).val();
        let input = $(this);
        $('.liveSearch').empty();
        if(item !== '') {
            $.ajax({
                url: '/lipadmin/'+url+'/live_search',
                method: 'GET',
                data: {q: item},
                success: function (res) {
                    input.parents('.card-body').siblings('.table-responsive').find('tbody').html(res);
                }
            })
        }
        else{
            input.parents('.card-body').siblings('.table-responsive').find('tbody').html('');
        }
    })
});

$(document).on('click', '.addToRating',function () {
    let name = $(this).data('name');
    let id = $(this).data('id');
    let type = $(this).data('type');
    let data = type+'_'+id;

    if(validateRating(data)) {
        $('#sortable' ).draggable;
        $('#sortable').append('<li class="list-group-item ui-state-default" data-id="' + data + '"><span class="badge badge-pill badge-primary">'+newIndex()+'</span>' + name + '<i class="fas fa-times delFromRate"></i></li>');
        $('#sortable').sortable("refresh");
        $('#sortable').sortable("refreshPositions");
        saveRating( $('#sortable').sortable("toArray",{"attribute":"data-id"}));
    }
});

$(document).on('click','.delFromRate',function () {
    $(this).parent().remove();
    $('#sortable' ).draggable;
    $('#sortable').sortable("refresh");
    $('#sortable').sortable("refreshPositions");
    updateIndex();
    removeFromRating($(this).parent().data('id'));
});

function validateRating(data) {
   let list = $('#sortable').sortable("toArray",{"attribute":"data-id"});
   if(list.indexOf(data) !== -1) {
       alert('Такой рецепт или продукт уже есть в списке');
       return false;
   }
   if(list.length === 9){
       alert('Рэйтинг может состоять из только из 9 товаров');
       return false;
   }

   return true;
}

function newIndex() {
    $('#sortable' ).draggable;
    let list = $('#sortable').sortable("toArray",{"attribute":"data-id"});
    return list.length + 1;
}
function updateIndex(){
    $('#sortable' ).draggable;
    let list = $('#sortable').sortable("toArray",{"attribute":"data-id"});
    $('#sortable').children().each(function () {
        $(this).find('span').text(list.indexOf($(this).data('id')) + 1);
    })
}

function saveRating(data) {
    $.ajax({
        url: '/lipadmin/rating/update',
        type: 'POST',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify(data),
        success: function () {
            console.log('yes!!')
        }
    });
}

function removeFromRating(data) {
    $.ajax({
        url: '/lipadmin/rating/remove',
        type: 'DELETE',
        data: {id: data},
        success: function () {
            console.log('yes!!')
        }
    });
}