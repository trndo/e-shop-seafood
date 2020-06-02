const $ = require('jquery');


$(document).ready(function () {
   $(document).on('click','.editSupply',function () {
       let data = {
           id: $(this).data('id'),
           quantity: $('#quantity-'+$(this).data('id')).val()
       };
       console.log(data,JSON.stringify(data));

       $.ajax({
           url: '/lipadmin/supply/edit',
           type: 'POST',
           dataType: 'json',
           contentType: 'application/json',
           success: function (res) {
                $('#success-'+data.id).fadeIn(300);
                setTimeout(function () {
                    $('#success-'+data.id).fadeOut(300);
                },3000)
           },
           data: JSON.stringify(data)
       });
   })
});