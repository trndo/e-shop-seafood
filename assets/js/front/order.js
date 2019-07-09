import 'air-datepicker/dist/css/datepicker.min.css';
import 'pickerjs/dist/picker.min.css';
import 'air-datepicker/dist/js/datepicker';
import Picker from 'pickerjs/dist/picker';
import $ from 'jquery';

$('.date-input').datepicker();

new Picker(document.querySelector('.time-input'), {
    format: 'HH:mm',
    headers: true,
    text: {
        title: 'Выбирете время',
    },
});

$('.delivery').click(function () {
    $('.self-delivery').removeClass('delivery-active');
    $(this).addClass('delivery-active');
});

$('.self-delivery').click(function () {
    $('.delivery').removeClass('delivery-active');
    $(this).addClass('delivery-active');
});