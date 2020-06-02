import $ from 'jquery';

$(document).ready(function () {
    let inputVal = $('#order_info_totalPrice').val();
    $('#order_info_totalPrice').on('blur',function () {
        $('#order_info_totalPrice').val(inputVal);
    })


})