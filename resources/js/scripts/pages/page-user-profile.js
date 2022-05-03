/*===============================================================================
    File Name: page-user-profile.js
    Description: Users manage their account
    ----------------------------------------------------------------------------
    Item Name: Exercise
    Author: Châu Ngọc Long
================================================================================*/

(function ($, document, window) {
    'use strict';

    $(function () {
        setToken();

        const url = JSON.parse($('[name="js_url"').val());

        console.log(url.updateAvatar);

        // Caching elements
        let $birthdayInput = $('#birthday');
        let $accountForm = $('#accountForm');
        let $changePasswordForm = $('#changePasswordForm');
        let $uploadAvatarBtn = $('#avatar');
        let $avatarPreview = $('#avatarPreview');

        // Init date picker
        if ($birthdayInput.length) {
            $birthdayInput.flatpickr({
                dateFormat: 'd-m-Y',
                onReady: function (selectedDates, dateStr, instance) {
                    if (instance.isMobile) {
                        $(instance.mobileInput).attr('step', null);
                    }
                }
            });
        }


        // Update profile
        const updateProfileSubmit = function (event) {
            event.preventDefault();

            let $this = $(this);

            if ($this.valid()) {
                $.ajax({
                    url: url.profileUpdate,
                    method: 'PUT',
                    data: $this.serializeArray(),
                    dataType: 'JSON'
                }).done((response) => {
                    // display message
                    showToast(response);

                    // update user info on navbar
                    $('.user-nav .user-name')
                        .html(response.data.profile.fullName);
                }).fail((xhr) => {
                    if (xhr.status === 422) {
                        displayValidateError(
                            $accountForm,
                            xhr.responseJSON.errors,
                            {
                                fields: {
                                    username: '',
                                    first_name: '',
                                    last_name: '',
                                    email: '',
                                    gender: '',
                                    phone: '',
                                    address: '',
                                    birthday: ''
                                }
                            }
                        );
                    } else {
                        showToast(xhr, 'error');
                    }
                });
            }
        };

        // Change password
        const changePasswordSubmit = function (event) {
            event.preventDefault();

            let $this = $(this);

            if ($this.valid()) {
                $.ajax({
                    url: '/change-password',
                    data: $this.serializeArray(),
                    method: 'PATCH',
                    dataType: 'JSON'
                }).done((response) => {
                    showToast(response);

                    // Reset form
                    $changePasswordForm.trigger('reset');
                }).fail((xhr) => {
                    if (xhr.status === 422) {
                        displayValidateError(
                            $changePasswordForm,
                            xhr.responseJSON.errors,
                            {
                                fields: {
                                    current_password: '.input-group',
                                    new_password: '.input-group',
                                    confirm_new_password: '.input-group',
                                }
                            }
                        );
                    } else {
                        showToast(xhr, 'error');
                    }
                });
            }
        };

        // Upload avatar
        const uploadAvatarChange = function (event) {
            console.log('here');

            let formData = new FormData();

            formData.append('avatar', event.target.files[0]);

            previewImage($(this), $avatarPreview);

            $.ajax({
                url: url.updateAvatar,
                method: 'POST',
                processData: false,
                cache: false,
                contentType: false,
                data: formData,
                dataType: 'JSON',
            }).done((response) => {
                showToast(response);

                // Update src image in navbar and preview
                $avatarPreview.attr('src', response.data.path);
                $('.dropdown-user .avatar img').attr('src', response.data.path);

                // Clear error
                $('#avatar_error').removeClass('error').html('');
            }).fail((xhr) => {
                if (xhr.status === 422) {
                    displayValidateError(
                        null,
                        xhr.responseJSON.errors,
                        {
                            fields: {
                                avatar: ''
                            }
                        }
                    );
                } else {
                    showToast(xhr, 'error');
                }
            });
        }


        // Update profile
        $accountForm.on('submit', updateProfileSubmit);

        // Change password
        $changePasswordForm.on('submit', changePasswordSubmit);

        $uploadAvatarBtn.on('change', uploadAvatarChange);

    });
})(jQuery, document, window);
