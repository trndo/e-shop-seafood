import $ from "jquery";

require('../css/sb-admin-2.css');
const jQuery = require('jquery');
require('bootstrap');
import bsCustomFileInput from 'bs-custom-file-input';
import './plugins/jquery.abacus.min';
import 'jquery-mask-plugin';

let totalSum = $('#order_info_totalPrice').val();
$('#order_info_totalPrice').abacus(totalSum);

(function ($) {
    "use strict"; // Start of use strict
    $(document).ready(function () {
        bsCustomFileInput.init()

    });

    $('.abacus-style').change(function () {

        let totalPrice = $('#order_info_totalPrice');
        let sum = 0;

        $('.abacus-style').each(function () {
            let product = $(this).parent().prev();
            let quantity = Number($(this).val());
            let productPrice = Number(product.val());
            let receiptPrice = Number(product.prev().val());

            if (receiptPrice !== null && product.hasClass('receipt_product') === true) {

                console.log(receiptPrice, productPrice);
                sum += (Math.ceil(quantity) * receiptPrice) + (quantity * productPrice);
                console.log(sum)
            } else {
                sum += quantity * productPrice;
                console.log('a' + sum)
            }
        });
        console.log(sum);
        totalSum = totalPrice.val(sum);
        totalPrice.abacus(totalSum.val());
    });

    $('.fa-trash-alt').click(function () {
        let id = $(this).data('id');
        let trash = $(this);
        let tr = $('tr[data-detail="' + id + '"]');

        $.ajax({
            type: "DELETE",
            url: "/lipadmin/deleteOrderDetail/" + id,
            success: function (res) {
                $('.orderTotalPrice').text('Cумма: ' + res.totalPrice);
                console.log(res.totalPrice);
                tr.remove();
            }
        })
    });

    $('.search-name').click(function () {
        $(this).parents('form').submit();
    });

    // Toggle the side navigation
    $("#sidebarToggle, #sidebarToggleTop").on('click', function (e) {
        $("body").toggleClass("sidebar-toggled");
        $(".sidebar").toggleClass("toggled");
        if ($(".sidebar").hasClass("toggled")) {
            $('.sidebar .collapse').collapse('hide');
        }
        ;
    });

    // Close any open menu accordions when window is resized below 768px
    $(window).resize(function () {
        if ($(window).width() < 768) {
            $('.sidebar .collapse').collapse('hide');
        }
        ;
    });

    // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
    $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function (e) {
        if ($(window).width() > 768) {
            var e0 = e.originalEvent,
                delta = e0.wheelDelta || -e0.detail;
            this.scrollTop += (delta < 0 ? 1 : -1) * 30;
            e.preventDefault();
        }
    });

    // Scroll to top button appear
    $(document).on('scroll', function () {
        var scrollDistance = $(this).scrollTop();
        if (scrollDistance > 100) {
            $('.scroll-to-top').fadeIn();
        } else {
            $('.scroll-to-top').fadeOut();
        }
    });

    // Smooth scrolling using jQuery easing
    $(document).on('click', 'a.scroll-to-top', function (e) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: ($($anchor.attr('href')).offset().top)
        }, 1000, 'easeInOutExpo');
        e.preventDefault();
    });

    $('.phone').mask('+38(000)000-00-00', {placeholder: "+38(___)___-__-__"});

    $(document).on('click', '.category-adjust', function () {
        let tbody = $('tbody');
        let categoryId = $(this).data('id');
        tbody.empty();
        console.log(categoryId);
        $.ajax({
            type: "POST",
            url: "/orderAdjustment/showItems",
            data: {
                categoryId: categoryId
            }, success(res) {
                tbody.append(res);
            }
        })
    });

    $(document).on('click', '.addItem', function () {
        let button = $(this).data('item');
        let radio = $('input[name="size"]:checked');
        let productAttr = $('p[data-item="' + button + '"]');
        let receiptAttr = $('p[data-receipt="' + button + '"]');

        let receiptItem = radio.data('item');
        let productId = radio.val();

        let productItem = productAttr.data('item');
        let noSizeReceiptItem = receiptAttr.data('receipt');
        let noSizeReceiptValue = receiptAttr.data('value');
        let orderId = $('#order-number').text();

        if (receiptItem === undefined && noSizeReceiptItem) {
            receiptItem = noSizeReceiptItem;
            productId = noSizeReceiptValue;
        }

        if (productId) {
            productItem = productId
        }

        console.log('Product',productItem,productId,button);
        console.log('Receipt',receiptItem,productItem,button);

        if (receiptItem === button || productItem === button) {
            console.log("gg");
            $.ajax({
                type: "POST",
                url: "/orderAdjustment/checkProductReservation",
                data: {
                    productId: productItem,
                    receiptId: receiptItem,
                    orderId: orderId
                }, success(res) {
                    radio.prop("checked", false);
                    if (res.status === false)
                        alert(res.message);
                    if (res.status === true)
                         window.location.href = res.order
                }
            })
        }
        radio.prop("checked", false);

    });

    $(document).on('click', '.addOrderDetail', function () {
        let thisButton = $(this);
        let button = $(this).data('item');
        let value = $(this).prev().val();
        let productId = $(this).parent().siblings('.prodId').data('item');
        let receiptDetail = $('p[data-receipt="' + button + '"]').data('receipt');
        let productDetail = $('p[data-product="' + button + '"]').data('product');
        let orderId = $('#order-number').text();

        if (!productDetail) {
            productDetail = productId;
        }

        if (receiptDetail === button || productDetail === button) {
            $.ajax({
                type: "POST",
                url:"/orderAdjustment/changeProductQuantity",
                data: {
                    value: value,
                    receiptId: receiptDetail,
                    productId: productDetail,
                    orderId: orderId
                }, success (res) {
                    console.log(res);
                    $('#order_info_totalPrice').val(res.totalSum);
                    thisButton.parent().siblings('.prodId').children('.product-reserve').text('Доступно в резерве: '+res.reservation);
                }
            })
        }
        console.log('Product',productDetail,productId,value,button);
        console.log('Receipt',receiptDetail,productId,value,button);

    });

    $('#searchItem').keyup(function (e) {
        let item = $(this).val();
        if (item !== '') {
            $('.forSearch').each(function () {
                let name = $(this).data('search');
                let regex = new RegExp('.*' + item + '.*', "i");

                if (!name.match(regex)) {
                    $(this).hide();
                } else
                    $(this).show();
            })
        } else {
            $('.forSearch').each(function () {
                $(this).show();
            })
        }
    });

    $('input[type=checkbox]').change(function () {
        let  additions=  $('#additions');
        if (this.checked) {
            additions.removeClass('notChecked');
            additions.addClass('visible-inputs');
        } else {
            additions.removeClass('visible-inputs');
            additions.addClass('notChecked');
        }
    });

    var $collectionHolder;

// setup an "add a tag" link
    var $addTagButton = $('<button type="button" class="add_tag_link btn btn-facebook">Добавить характеристику</button><br>');
    var $newLinkLi = $('<li style="list-style-type: none;"></li>').append($addTagButton);

    $(document).ready(function() {
        // Get the ul that holds the collection of tags
        $collectionHolder = $('ul.tags');

        // add the "add a tag" anchor and li to the tags ul
        $collectionHolder.append($newLinkLi);

        // count the current form inputs we have (e.g. 2), use that as the new
        // index when inserting a new item (e.g. 2)
        $collectionHolder.data('index', $collectionHolder.find(':input').length);

        $addTagButton.on('click', function(e) {
            // add a new tag form (see next code block)
            var index = addTagForm($collectionHolder, $newLinkLi);
            if (index === 2)
                $addTagButton.remove();
        });

        function addTagForm($collectionHolder, $newLinkLi) {
            // Get the data-prototype explained earlier
            var prototype = $collectionHolder.data('prototype');

            // get the new index
            var index = $collectionHolder.data('index');

            var newForm = prototype;
            // You need this only if you didn't set 'label' => false in your tags field in TaskType
            // Replace '__name__label__' in the prototype's HTML to
            // instead be a number based on how many items we have
            // newForm = newForm.replace(/__name__label__/g, index);

            // Replace '__name__' in the prototype's HTML to
            // instead be a number based on how many items we have
            newForm = newForm.replace(/__name__/g, index);

            // increase the index with one for the next item
            $collectionHolder.data('index', index + 1);

            // Display the form in the page in an li, before the "Add a tag" link li
            var $newFormLi = $('<li></li>').append(newForm);
            $newLinkLi.before($newFormLi);

            return index;
        }
    });


})(jQuery); // End of use strict