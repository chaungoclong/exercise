// Override Message Validation
jQuery.extend(jQuery.validator.messages, {
    required: "Trường này không được để trống.",
    remote: "Vui lòng sửa trường này.",
    email: "Vui lòng nhập địa chỉ Emai hợp lệ.",
    url: "Vui lòng nhập URL hợp lệ.",
    date: "Vui lòng nhập ngày hợp lệ.",
    dateISO: "Vui lòng nhập ngày hợp lệ (ISO).",
    number: "Vui lòng nhập một số hợp lệ.",
    digits: "Vui lòng chỉ nhập các chữ số.",
    creditcard: "Vui lòng nhập số thẻ tín dụng hợp lệ.",
    equalTo: "Vui lòng nhập lại cùng một giá trị.",
    accept: "Vui lòng nhập giá trị có phần mở rộng hợp lệ.",
    maxlength: jQuery.validator.format("Vui lòng nhập không quá {0} ký tự."),
    minlength: jQuery.validator.format("Vui lòng nhập ít nhất {0} ký tự."),
    rangelength: jQuery.validator.format("Vui lòng nhập một giá trị có độ dài từ {0} đến {1} ký tự."),
    range: jQuery.validator.format("Vui lòng nhập giá trị từ {0} đến {1}"),
    max: jQuery.validator.format("Vui lòng nhập giá trị nhỏ hơn hoặc bằng {0}."),
    min: jQuery.validator.format("Vui lòng nhập giá trị lớn hơn hoặc bằng {0}."), extension: "Vui lòng nhập giá trị có phần mở rộng hợp lệ({0})"
});

// Custom Rule validation
// Check File Size
jQuery.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || ((element.files[0].size / 1000) <= param);
}, 'Kích thước tệp phải nhỏ hơn hoặc bằng {0} kB');

// Check Phone
jQuery.validator.addMethod('phone', function (value, element) {
    return this.optional(element) ||
        /(84|0[3|5|7|8|9])+([0-9]{8})\b/.test(value);
}, 'Vui lòng nhập số điện thoại hợp lệ');

// Function reverse string
const strReverse = (str) => {
    if (typeof str === 'string') {
        return str.split('').reverse().join('');
    }

    return '';
}

// Function reverse date string
const dateReverse = (date) => {
    if (typeof date === 'string') {
        return date.split(/[\.\\\-\/]+/).reverse().join('-');
    }

    return '';
}

// Check Date Format dd/mm/yyyy || dd-mm-yyyy
$.validator.addMethod('isDate', function (value, element) {
    let regex =
        /^(?=\d)(?:(?:31(?!.(?:0?[2469]|11))|(?:30|29)(?!.0?2)|29(?=.0?2.(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(?:\x20|$))|(?:2[0-8]|1\d|0?[1-9]))([-.\/])(?:1[012]|0?[1-9])\1(?:1[6-9]|[2-9]\d)?\d\d(?:(?=\x20\d)\x20|$))?(((0?[1-9]|1[012])(:[0-5]\d){0,2}(\x20[AP]M))|([01]\d|2[0-3])(:[0-5]\d){1,2})?$/;

    return this.optional(element) || regex.test(value);
}, 'Vui lòng nhập ngày hợp lệ');

// Check Field is Unique
$.validator.addMethod('unique', function (value, element, selector = undefined) {
    let count = 0;
    let $listField = undefined;

    if (selector === undefined) {
        $listField = $(`[name="${element.name}"]`);
    } else {
        $listField = $(selector);
    }

    $listField.each(function () {
        ($(this).val() === value) && (count++);
    });

    return (count === 1) || (count === 0);
}, 'Trường này bị lặp lại');

/**
 * Toast config reuse
 */
let Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 5000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

/**
 * Show Toast error for Ajax
 * @param {*} xhr
 * @param {*} callback
 * @param {*} options
 */
function showToastError(xhr, callback = undefined, options = {}) {
    let defaultOptions = {
        fieldMessage: 'message',
        icon: 'error'
    };

    // Merge options
    let _options = $.extend({}, defaultOptions, options);

    // init toast error
    let toastError = Toast.fire({
        title: xhr.responseJSON[_options['fieldMessage']],
        icon: _options['icon']
    });

    // Execute callback if callback is function
    if (typeof callback === 'function') {
        toastError.then(callback);
    }
}


/**
 * Show Toast
 * @param {*} xhr
 * @param {*} callback
 * @param {*} options
 */
function showToast(
    responseOrXhr,
    type = 'success',
    callback = undefined,
    fieldMessage = 'message'
) {
    let title = '';

    if (responseOrXhr.responseJSON !== undefined) {
        title = responseOrXhr.responseJSON[fieldMessage];
    } else {
        title = responseOrXhr[fieldMessage];
    }

    if (title === '') {
        title = (type === 'error') ? 'Đã có lỗi xảy ra' : 'Thành công';
    }

    // init toast
    let toast = Toast.fire({
        title: title,
        icon: type.toLowerCase().trim(),
        showCloseButton: true
    });

    // Execute callback if callback is function
    if (typeof callback === 'function') {
        toast.then(callback);
    }
}


/**
 * Display error validate from ajax
 * @param {*} errors
 * @param {*} options
 */
function displayValidateError(form, errors, options = {}) {
    let defaultOptions = {
        fields: {},
        classInput: 'error',
        classWrapper: 'is-invalid',
        classMessage: 'error'
    }

    let _options = $.extend({}, defaultOptions, options);

    let $form = null;

    if (form !== null) {
        $form = (form instanceof jQuery) ? form : $(form);
    }

    for (let field of Object.keys(_options.fields)) {
        let invalid = (errors[field] !== undefined);
        let message = (errors[field] !== undefined) ? errors[field][0] : '';

        let $input = ($form !== null)
            ? $form.find(`[name="${field}"]`)
            : $(`#${field}`);

        let $message = ($form !== null)
            ? $form.find(`#${field}_error`)
            : $(`#${field}_error`);

        // Input
        $input
            .toggleClass(_options.classInput, invalid)
            .closest(_options.fields[field])
            .toggleClass(_options.classWrapper, invalid);

        // Message error
        $message
            .html(message)
            .toggleClass(_options.classMessage, invalid)
            .show();

        console.log($input, $message, errors, field);
    }
}


// Preview Image
function previewImage(fileInput, preview) {
    let $fileInput = (fileInput instanceof jQuery) ? fileInput : $(fileInput);
    let $preview = (preview instanceof jQuery) ? preview : $(preview);
    let file = $fileInput[0].files[0];
    let fileReader = new FileReader();

    fileReader.addEventListener('load', function (event) {
        $preview.attr('src', event.target.result);
    });

    fileReader.readAsDataURL(file);
}

// Reset Form
const resetForm = (form) => {
    let $form = (form instanceof jQuery) ? form : $(form);

    let $validator = $form.validate();

    $form.trigger('reset');

    $validator.resetForm();

    $form.find('.error, .is-invalid').removeClass('error is-invalid');
}

// Show first error validate
const showFirstError = (xhr) => {
    let errors = xhr.responseJSON.errors;

    Toast.fire({
        title: errors[Object.keys(errors)[0]][0],
        icon: 'error'
    });
}


// Bind JSON To Form
(function ($) {
    $.fn.jsonToForm = function (data, callbacks) {
        var formInstance = this;

        var options = {
            data: data || null,
            callbacks: callbacks,
        };

        if (options.data != null) {
            $.each(options.data, function (k, v) {
                var elements = $('[name^="' + k + '"]', formInstance);

                if (options.callbacks != null && options.callbacks.hasOwnProperty(k)) {
                    options.callbacks[k](v);
                    return;
                }

                $(elements).each(function (index, element) {
                    if (Array.isArray(v)) {
                        v.forEach(function (val) {
                            $(element).is("select")
                                ? $(element)
                                    .find("[value='" + val + "']")
                                    .prop("selected", true)
                                : $(element).val() == val
                                    ? $(element).prop("checked", true)
                                    : "";
                        });
                    } else if ($(element).is(":checkbox") || $(element).is(":radio")) {
                        // checkbox group or radio group
                        $(element).val() == v ? $(element).prop("checked", true) : "";
                    } else {
                        $('[name="' + k + '"]', formInstance).val(v);
                    }
                });
            });
        }
    };
})(jQuery);


// Get data from input hidden store data from PHP
function php(name) {
    const PREFIX = '#passing_data_from_PHP_to_JavaScript_';

    return $(PREFIX + name).val();
}
