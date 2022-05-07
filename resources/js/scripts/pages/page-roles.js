(function ($, document, window) {
    'use strict';

    $(function () {
        setToken();

        let $createRoleModal = $('#createRoleModal');
        let createRoleModal = new bootstrap.Modal($createRoleModal[0]);
        let $createRoleForm = $('#createRoleForm');

        let $editRoleModal = $('#editRoleModal');
        let editRoleModal = new bootstrap.Modal($editRoleModal[0]);
        let $editRoleForm = $('#editRoleForm');

        // Validate Options
        let validateOptions = {
            rules: {
                name: {
                    required: true,
                    minlength: 2
                },
                slug: {
                    required: true,
                },
                'permissions[]': {
                    required: true,
                },
            }
        }

        let $listRoleContainer = $('#listRole');

        // Init DataTables
        let roleTable = $('#listRoleTbl').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: $listRoleContainer.data('url-datatables'),
                data: (d) => {
                    d.search = $('#listRoleTbl_filter input').val();
                    d.sort = $('[name="sort_type"]').val();
                }
            },
            columns: [{
                data: 'html'
            }],

            initComplete: function (settings, json) {
                // show new container for data
                $listRoleContainer.insertBefore('#listRoleTbl');

                $listRoleContainer.show();
            },
            rowCallback: function (row, role) {
                $listRoleContainer.append(role.html);
            },
            preDrawCallback: function (settings) {
                // clear list before draw
                $listRoleContainer.empty();
            }
        });


        // Open Create Modal
        const openCreateModal = function (event) {
            createRoleModal.show();
            resetForm($createRoleForm);

            let url = $(this).data('url-create');

            $.get(url)
                .done((response) => {
                    $createRoleForm.find('#permissions')
                        .select2({
                            data: response.data.permissionOptions
                        });
                }).fail((xhr) => {
                    showToast(xhr, 'error');
                });
        }

        // Create New Role
        const createNewRole = function (event) {
            event.preventDefault();

            let url = this.action;
            let data = $createRoleForm.serializeArray();

            if ($createRoleForm.valid()) {
                $.post(url, data, 'JSON')
                    .done((response) => {
                        roleTable.ajax.reload();

                        createRoleModal.hide();

                        $createRoleForm.trigger('reset');

                        showToast(response);
                    }).fail((xhr) => {
                        if (xhr.status === 422) {
                            showFirstError(xhr);
                        } else {
                            showToast(xhr);
                        }
                    });
            }
        }

        // Open Edit Role Modal
        const openEditModal = function (event) {
            editRoleModal.show();
            resetForm($editRoleForm);

            let $this = $(this);
            let url = $this.data('url-edit');

            // Set Action Update For Form Edit
            $editRoleForm.attr('action', $this.data('url-update'));

            $.get(url)
                .done((response) => {
                    let role = response.data.role;
                    let permissionOpts = response.data.permissionOptions;
                    let idPermissionsSelected = response.data.idPermissionsSelected;

                    // Bind Data To Edit Form
                    $editRoleForm.find('#name')
                        .val(role.name);

                    $editRoleForm.find('#slug')
                        .val(role.slug)
                        .prop('disabled', true);

                    // Fetch Permission Options And Select Role's Permission
                    $editRoleForm.find('#permissions')
                        .select2({
                            data: permissionOpts
                        })
                        .val(idPermissionsSelected)
                        .trigger('change');
                });
        }

        // Update Role
        const updateRole = function (event) {
            event.preventDefault();

            let url = this.action;
            let data = $editRoleForm.serializeArray();

            if ($editRoleForm.valid()) {
                $.ajax({
                    url: url,
                    method: 'PUT',
                    data: data,
                    dataType: 'JSON'
                }).done((response) => {
                    roleTable.ajax.reload(null, false);

                    editRoleModal.hide();

                    $editRoleForm.trigger('reset');

                    showToast(response);
                }).fail((xhr) => {
                    if (xhr.status === 422) {
                        showFirstError(xhr);
                    } else {
                        showToast(xhr, 'error');
                    }
                });
            }
        }


        // Delete Role
        const deleteRole = function (event) {
            Swal.fire({
                title: 'Bạn có muốn xóa Vai Trò này',
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
                        roleTable.ajax.reload(null, false);

                        showToast(response);
                    }).fail((xhr) => {
                        showToast(xhr, 'error');
                    });
                }
            });
        }

        // Validate Form
        $('#createRoleForm, #editRoleForm')
            .each(function () {
                $(this).validate(validateOptions);
            });

        // Open Create Role Modal
        $('#createRole').on('click', openCreateModal);

        // Create New Role
        $createRoleForm.on('submit', createNewRole);

        // Open Edit Role Modal
        $(document).on('click', '.role-edit', openEditModal);

        // Update Role
        $editRoleForm.on('submit', updateRole);

        // Delete Role
        $(document).on('click', '.role-delete', deleteRole);

        $('.sort-item').on('click', function () {
            $('[name="sort_type"]').val($(this).data('sort'));

            $('#sortBtn').text($(this).text());

            roleTable.ajax.reload();
        });

    });
})(jQuery, document, window);
