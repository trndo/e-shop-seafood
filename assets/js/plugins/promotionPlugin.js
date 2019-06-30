import $ from 'jquery';

export default function changeOptionBy(categoryInput, itemInput) {
    $(document).ready(function () {
        let category = $(categoryInput);
        category.on('change', function () {
            let form = $(this).closest('form');
            let data = {};
            data[category.attr('name')] = category.val();

            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: data,
                success: function (html) {
                    $(itemInput).replaceWith(
                        $(html).find(itemInput)
                    );
                }
            });
        });
    });
}