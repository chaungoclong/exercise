(function ($, document) {
    'use strict';

    $(function () {
        setToken();

        let $userList = $('#userList');

        // Init DataTables
        let $userTable = $('#userTbl').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: $userList.data('url-datatables'),
                data: (d) => {
                    d.search = $('#userTbl_filter input').val();
                    d.role_id = $('#userRole').val();
                    d.status = $('#userStatus').val();
                    d.sort = $('[name="sort_type"]').val();
                }
            },
            columns: [{
                data: 'html'
            }],

            initComplete: function (settings, json) {
                // show new container for data
                $userList.insertBefore('#userTbl');

                $userList.show();
            },
            rowCallback: function (row, data) {
                $userList.append(data.html);
            },
            preDrawCallback: function (settings) {
                // clear list before draw
                $userList.empty();
            }
        });

        const UPDATE_ACTION = 'update';
        const STORE_ACTION = 'store';

        let $saveUserModal = $('#saveUserModal');
        let saveUserModal = new bootstrap.Modal($saveUserModal[0]);
        let $saveUserForm = $('#saveUserForm');
        let $selectRole = $('#role_id');
        let _action = STORE_ACTION;
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

        // Function Open Create Modal
        const openCreateModal = function () {
            openModal();
            _action = STORE_ACTION;
            _url = $(this).data('url-store');

            // Show Password Field
            $('.password-wrap')
                .show()
                .find('#password, #confirm_password')
                .prop('disabled', false);

            // Set Modal Title
            $('.save-modal-title').text('Create User');

            // Get Data Create
            $.get($(this).data('url-create'))
                .done((response) => {
                    $selectRole
                        .wrap('<div class="position-relative"></div>')
                        .select2({
                            data: response.data.roleOptions
                        })
                        .on('change', function () {
                            $(this).valid();
                        });
                }).fail((xhr) => {
                    showToast(xhr, 'error');
                });
        }

        // Function Open Edit Modal
        const openEditModal = function () {
            openModal();
            _action = UPDATE_ACTION;
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
            if (_action === UPDATE_ACTION) {
                formData.append('_method', 'PUT');
            }

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
                    if (_action === 'store') {
                        $userTable.ajax.reload();
                    } else {
                        $userTable.ajax.reload(null, false);
                    }

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
                        $userTable.ajax.reload(null, false);

                        showToast(response);
                    }).fail((xhr) => {
                        showToast(xhr, 'error');
                    });
                }
            });
        }

        // Function Switch User Status
        const switchStatus = function () {
            Swal.fire({
                title: 'Bạn có muốn thay đổi Trạng thái của User này',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Không!',
                confirmButtonText: 'Có!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = $(this).data('url-switch');

                    $.ajax({
                        url: url,
                        method: 'PATCH',
                        dataType: 'JSON',
                        data: {
                            status: $(this).prop('checked') ? 1 : 0
                        }
                    }).done((response) => {
                        $userTable.ajax.reload(null, false);

                        showToast(response);
                    }).fail((xhr) => {
                        showToast(xhr, 'error');

                        // Reset status
                        $(this).prop('checked', !$(this).prop('checked'));
                    });
                } else {
                    // Reset status
                    $(this).prop('checked', !$(this).prop('checked'));
                }
            });
        }

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

        // Filter By Status Or User's Role
        $('#userStatus, #userRole').on('change', function () {
            $userTable.ajax.reload();
        });

        // Sort
        $('.sort-item').on('click', function () {
            $('[name="sort_type"]').val($(this).data('sort'));

            $userTable.ajax.reload();

            $('#sortBtn').text($(this).text());
        });

        // Open Create Modal
        $('#createUser').on('click', openCreateModal);

        // Open Edit Modal
        $(document).on('click', '.user-edit', openEditModal);

        // Save User
        $saveUserForm.on('submit', () => false);
        $('#saveBtn').on('click', saveUser);

        // Delete User
        $(document).on('click', '.user-delete', deleteUser);

        // Switch Status
        $(document).on('click', '.status-switch', switchStatus);
    });
})(jQuery, document);
