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
