import '../../css/lipinskie-raki/main.css';
import $ from 'jquery';

$(document).ready(function () {
    $('.in_basket').on('click',function () {
        let type = $(this).data('type');
        let slug = $(this).data('name');

        $.ajax({
           type: 'POST',
           url: '/addToCart',
           data: {
               type: type,
               slug: slug
           },
           success: function (res) {
                console.log(res.name,res.size,res.unit,res.titlePhoto,res.price);
           }
        });
    })
})