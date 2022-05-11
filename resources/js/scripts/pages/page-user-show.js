(($, document) => {
    $(function () {
        setToken();

        let $saveUserModal = $('#saveUserModal');
        let saveUserModal = new bootstrap.Modal($saveUserModal[0]);
        let $saveUserForm = $('#saveUserForm');
        let $selectRole = $('#role_id');
        let _url = '';

        // Validate Form
        $('#saveUserForm').each(function () {
            $(this).validate({
                rules: {
                    first_name: {
                        required: true,
                    },
                    last_name: {
                        required: true,
                    },
                    username: {
                        required: true,
                    },
                    password: {
                        required: true,
                        minlength: 8
                    },
                    confirm_password: {
                        required: true,
                        minlength: 8,
                        equalTo: '#password'
                    },
                    address: {
                        required: true,
                    },
                    avatar: {
                        filesize: 600,
                        extension: 'jpg|png'
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    phone: {
                        required: true,
                        phone: true
                    },
                    role_id: {
                        required: true
                    },
                    gender: {
                        required: true
                    },
                    birthday: {
                        required: true
                    }
                }
            })
        });

        $('.datatable-project').DataTable({
            dom: `
        <"d-flex justify-content-between px-1"
            <l>
            <f>
        >
        t
        <"d-flex justify-content-between px-1"
            <i>
            <p>
        >`,
        });

        // Function Open Modal and Reset Form
        const openModal = () => {
            resetForm($saveUserForm);
            saveUserModal.show();
        }

        // Function Close Modal and Reset Form
        const closeModal = () => {
            resetForm($saveUserForm);
            saveUserModal.hide();
        }

        // Function Open Edit Modal
        const openEditModal = function () {
            openModal();
            _url = $(this).data('url-update');

            // Hide Password Field
            $('.password-wrap')
                .hide()
                .find('#password, #confirm_password')
                .prop('disabled', true);

            // Set Modal Title
            $('.save-modal-title').text('Edit User');

            $.get($(this).data('url-edit'))
                .done((respone) => {
                    // Fill Form By JSON
                    let user = respone.data.user;
                    let roleOptions = respone.data.roleOptions;
                    let idRoleSelected = respone.data.idRoleSelected;

                    $saveUserForm.jsonToForm(user, {
                        'avatar': (src) => {
                            $('#avatarPreview').attr('src', src);
                        },
                    });

                    // Init Select2
                    $selectRole
                        .wrap('<div class="position-relative"></div>')
                        .select2({
                            data: roleOptions
                        })
                        .val(idRoleSelected)
                        .trigger('change')
                        .on('change', function () {
                            $(this).valid();
                        });
                });
        };

        // Function Save User
        const saveUser = function () {
            let formData = new FormData($saveUserForm[0]);

            let files = $('#avatar')[0].files;

            // Append Avatar If Exist
            if (files.length) {
                formData.append('avatar', files[0]);
            }

            // If Update then add field 'method' = 'PUT'(since FormData cannot be sent using PUT method so use POST method and fake PUT method with '_method' field
            formData.append('_method', 'PUT');

            if ($saveUserForm.valid()) {
                $.ajax({
                    url: _url,
                    data: formData,
                    method: 'POST',
                    dataType: 'JSON',
                    contentType: false,
                    processData: false
                }).done((response) => {
                    // Reload List User
                    window.location.reload();

                    closeModal();

                    showToast(response);
                }).fail((xhr) => {
                    if (xhr.status === 422) {
                        showFirstError(xhr);
                    } else {
                        showToast(xhr, 'error');
                    }
                });
            }
        };

        // Function Delete User
        const deleteUser = function () {
            Swal.fire({
                title: 'Bạn có muốn xóa User này',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Không!',
                confirmButtonText: 'Có!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = $(this).data('url-delete');

                    $.ajax({
                        url: url,
                        method: 'DELETE',
                        dataType: 'JSON'
                    }).done((response) => {
                        window.location.href = php('list_user_url');

                        showToast(response);
                    }).fail((xhr) => {
                        showToast(xhr, 'error');
                    });
                }
            });
        };

        // Preview Image
        $('#avatar').on('change', function () {
            previewImage($(this), '#avatarPreview');
        });

        // Init date picker
        $('#birthday').flatpickr({
            allowInput: true,
            dateFormat: 'd-m-Y',
            onReady: function (selectedDates, dateStr, instance) {
                if (instance.isMobile) {
                    $(instance.mobileInput).attr('step', null);
                }
            }
        });

        // Open Edit Modal
        $('.user-edit').on('click', openEditModal);

        // Update User
        $saveUserForm.on('submit', () => false);
        $('#saveBtn').on('click', saveUser);

        // Delete User
        $('.user-delete').on('click', deleteUser);
    });
})(jQuery, document);
