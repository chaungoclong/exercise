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

    $form.find('.error').removeClass('error');
}

// Show first error validate
const showFirstError = (xhr) => {
    let errors = xhr.responseJSON.errors;

    Toast.fire({
        title: errors[Object.keys(errors)[0]][0],
        icon: 'error'
    });
}
