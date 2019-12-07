import 'air-datepicker/dist/css/datepicker.min.css';
import 'pickerjs/dist/picker.min.css';
import 'air-datepicker/dist/js/datepicker';
import 'timepicker/jquery.timepicker.css';
import Picker from 'pickerjs/dist/picker';
import $ from 'jquery';
import 'timepicker/jquery.timepicker';
import 'jquery-mask-plugin';

let today = new Date();
let tomorrow = new Date();
tomorrow.setDate(today.getDate()+1);

$('.date-input').datepicker({
    minDate: tomorrow
});

$('.date-input').keypress(function (e) {
    e.preventDefault();
});
$('.date-input').attr('readonly','readonly');

if(screen.width < 992)
new Picker(document.querySelector('.time-input'), {
    format: 'HH:mm',
    headers: true,
    text: {
        title: 'Выбирете время',
    },
});
else {
    $('.time-input').timepicker({
        'scrollDefault': 'now',
        'timeFormat': 'H:i',
        'step': 10
    });
    $('.time-input').keypress(function(e) {
        e.preventDefault();
    })
}

$('.delivery').click(function () {
    $('.self-delivery').removeClass('delivery-active');
    $(this).addClass('delivery-active');
    $('.order-address').css('display','block');
    $('.lipinka-street').css('display','none')
});

$('.self-delivery').click(function () {
    $('.delivery').removeClass('delivery-active');
    $(this).addClass('delivery-active');
    $('.order-address').css('display','none');
    $('.lipinka-street').css('display','block')

});

$('.phone-mask').mask('+38(000)000-00-00');
