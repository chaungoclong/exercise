(($, document) => {
    $(function () {
        setToken();

        const ACTION_STORE = 'store';
        const ACTION_UPDATE = 'update';

        // Use for naming for select's name
        let _count = 0;
        let _action = ACTION_STORE;
        let _method = 'POST';
        let _url = '';

        // Store list options User
        let _userOptions = [];

        // Store list options Position
        let _positionOptions = [];

        let $projectList = $('#projectList');

        // DataTables
        let $projectTable = $('#projectTbl').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: $projectList.data('url-datatables'),
                data: (d) => {
                    d.search = $('#projectTbl_filter input')
                        .val();
                    d.sort = $('[name="sort_type"]').val();
                    d.user_id = $('#users').val();
                    d.status = $('#projectStatus').val();
                    d.my_project = $('#myProject').prop('checked') ? 1 : 0;
                }
            },
            columns: [{
                data: 'html'
            }],
            initComplete: function (settings, json) {
                // show new container for data
                $projectList
                    .insertBefore('#projectTbl');

                $projectList.show();
            },
            rowCallback: function (row, data) {
                $projectList.append(data.html);
            },
            preDrawCallback: function (settings) {
                // clear list before draw
                $projectList.empty();
            }
        });

        let $saveProjectModal = $('#saveProjectModal');
        let saveProjectModal = new bootstrap.Modal($saveProjectModal[0]);
        let $saveProjectForm = $('#saveProjectForm');

        let $wrapperRowInput = $('.wrapper-row-input');

        let $startDate = undefined;
        let $dueDate = undefined;

        // Template Row Input
        let rowInputTemplate = (count) => `
        <div class="row d-flex row-input align-items-start">
            <div class="col-5">
                <div class="mb-1">
                    <label class="form-label"
                        for="itemname">User</label>
                    <select name="users[${count}][]"
                        class="form-select user-select select2">
                        <option value="">Select User</option>
                    </select>
                </div>
            </div>

            <div class="col-5">
                <div class="mb-1">
                    <label class="form-label"
                        for="itemquantity">Position</label>
                    <select name="positions[${count}][]"
                        class="form-select position-select select2" multiple>
                    </select>
                </div>
            </div>

            <div class="col-2 mt-2">
               <button class="btn btn-danger delete-row-input">
                Delete
                </button>
            </div>
        </div>`;

        // Function Add New Row Input
        const addNewRowInput = function (event) {
            event.preventDefault();

            // Create new Row input
            $newRowInput = $(rowInputTemplate(_count));

            // Init Select 2 for all select in new row input
            $newRowInput
                .find('.user-select')
                .select2({
                    data: _userOptions
                })
                .on('change', function () {
                    $(this).valid();
                }).end()
                .find('.position-select')
                .select2({
                    data: _positionOptions
                })
                .on('change', function () {
                    $(this).valid();
                });

            // Add new row input to wrapper row input
            $wrapperRowInput.prepend($newRowInput);

            // increments count
            _count++;
        }

        // Function render Project Members row input from JSON
        const renderRowInput = (projectMembers) => {
            for (let userId of Object.keys(projectMembers)) {

                let $newRowInput = $(rowInputTemplate(_count));

                $newRowInput
                    .find('.user-select')
                    .select2({
                        data: _userOptions,
                    })
                    .val(userId)
                    .trigger('change')
                    .on('change', function () {
                        $(this).valid();
                    }).end()
                    .find('.position-select')
                    .select2({
                        data: _positionOptions
                    })
                    .val(projectMembers[userId])
                    .trigger('change')
                    .on('change', function () {
                        $(this).valid();
                    });

                $wrapperRowInput.prepend($newRowInput);

                _count++;
            }
        }

        // Function Delete Row Input
        const deleteRowInput = function () {
            $(this).closest('.row-input').remove();
        };

        // Function Open Modal
        const openModal = () => {
            resetForm($saveProjectForm);

            saveProjectModal.show();

            $wrapperRowInput.empty();
        }

        // Function Hide Modal
        const closeModal = () => {
            resetForm($saveProjectForm);

            saveProjectModal.hide();

            $wrapperRowInput.empty();
        }

        // Function Open Modal To Create
        const openModalToCreate = function (event) {
            openModal();

            _url = $(this).data('url-create');
            _method = 'POST';

            // Set Modal Title
            $('#saveProjectModalLabel').text('Create New Project');

            // Disable and Hide Status Field
            $('#status')
                .parent()
                .hide().end()
                .prop('disabled', true);

            // Enable Slug Field
            $('#slug').prop('disabled', false);

            $.get(_url)
                .done((response) => {
                    console.log(response);

                    _userOptions = response.data.userOptions;
                    _positionOptions = response.data.positionOptions;

                    // Set URL For Store
                    _url = $(this).data('url-store');

                })
                .fail((xhr) => {
                    showToast(xhr, 'error');

                    _url = '';
                });
        };

        // Function Open Modal To Update
        const openModalToUpdate = function (event) {
            openModal();

            _url = $(this).data('url-edit');
            _method = 'PUT';

            // Set Modal Title
            $('#saveProjectModalLabel').text('Update Project');

            // Enable and Show Status Field
            $('#status')
                .parent()
                .show().end()
                .prop('disabled', false);

            // Disable Slug Field
            $('#slug').prop('disabled', true);

            $.get(_url)
                .done((response) => {
                    // Options data
                    _userOptions = response.data.userOptions;
                    _positionOptions = response.data.positionOptions;
                    _statusOptions = response.data.statusOptions;

                    let project = response.data.project;
                    let projectMembers = project.project_members;

                    let positionGroupByUsers = projectMembers
                        .reduce((p, c) => {
                            p[c.user_id] = p[c.user_id] || [];
                            p[c.user_id].push(c.position_id);

                            return p;
                        }, {});

                    // Render Project Members By positionGroupByUsers
                    renderRowInput(positionGroupByUsers);

                    // Render Status Select
                    $('#status')
                        .select2({
                            data: _statusOptions
                        })
                        .val(project.status)
                        .trigger('change')
                        .on('change', function () {
                            $(this).valid();
                        });

                    // Bind Data From JSON to Form
                    $saveProjectForm.jsonToForm(project, {
                        'start_date': (value) => {
                            $startDate.setDate(value, true);
                        },
                        'due_date': (value) => {
                            $dueDate.setDate(value, true);
                        }
                    });

                    // Set URL For Update
                    _url = $(this).data('url-update');

                })
                .fail((xhr) => {
                    showToast(xhr, 'error');

                    _url = '';
                });
        }

        // Function Save Project
        const saveProject = function (event) {
            event.preventDefault();

            let $selectInput = $('.user-select, .position-select');

            // Validate if Project Members row input exist
            if ($selectInput.length) {
                $selectInput.each(function () {
                    let $this = $(this);

                    // Required
                    $this.rules('add', {
                        required: true,
                    });

                    // Unique
                    if ($this.is('.user-select')) {
                        $this.rules('add', {
                            unique: '.user-select'
                        });
                    }
                });
            }

            // Submit if Valid
            if ($saveProjectForm.valid()) {
                let data = $saveProjectForm.serialize();

                $.ajax({
                    url: _url,
                    method: _method,
                    data: data,
                    dataType: 'JSON'
                }).done((response) => {
                    // Reload go to first page when store
                    // Reload current page when update
                    if (_action === ACTION_STORE) {
                        $projectTable.ajax.reload();
                    } else {
                        $projectTable.ajax.reload(null, false);
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
        }

        // Function Delete Project
        const deleteProject = function (event) {
            Swal.fire({
                title: 'Xóa Dự án',
                html: `
                <div class="alert alert-warning">
                    <div class="alert-body">
                        Bạn có chắc muốn xóa Dự án này?<br>Mọi Phân công và Báo cáo của nó sẽ bị xóa theo
                    </div>
                </div>
                <input class="form-control"
                id="confirmDeleteProject"
                placeholder="Nhập 'delete' để xóa Dự án này">`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Không',
                preConfirm: (value) => {
                    if ($('#confirmDeleteProject').val() !== 'delete') {
                        Swal.showValidationMessage(
                            `Nhập 'delete' để xóa Dự án này`
                        );

                        return false;
                    }

                    return true;
                }
            }).then((confirm) => {
                if (confirm.isConfirmed) {
                    _url = $(this).data('url-delete');
                    _method = 'delete';

                    $.ajax({
                        url: _url,
                        method: _method,
                        dataType: 'JSON'
                    }).done((response) => {
                        $projectTable.ajax.reload(null, false);

                        showToast(response);
                    }).fail((xhr) => {
                        showToast(xhr, 'error');
                    });
                }
            });
        };

        // Validate
        $('#saveProjectForm').validate({
            rules: {
                name: {
                    required: true
                },
                slug: {
                    required: true
                },
                start_date: {
                    isDate: true,
                    required: true
                },
                due_date: {
                    isDate: true,
                    required: true,
                },
                duration: {
                    required: true
                },
                revenue: {
                    required: true
                },
                status: {
                    required: true
                },
                detail: {
                    required: true
                }
            }
        });

        // Init Date Picker
        if ($('#start_date').length) {
            $startDate = $('#start_date').flatpickr({
                dateFormat: 'd-m-Y',

            });

            console.log($startDate);
        }

        if ($('#due_date').length) {
            $dueDate = $('#due_date').flatpickr({
                dateFormat: 'd-m-Y',
                onOpen: function (selectedDates, dateStr, instance) {
                    let minDate = undefined;
                    let startAtStr = $('#start_date').val();

                    if (!/Invalid|NaN/.test(new Date(startAtStr))) {
                        minDate = new Date(startAtStr).fp_incr(1);
                    }

                    startAtStr = dateReverse(startAtStr);

                    if (!/Invalid|NaN/.test(new Date(startAtStr))) {
                        minDate = new Date(startAtStr).fp_incr(1);
                    }

                    if (minDate) {
                        instance.set('minDate', minDate);
                    }
                },
            });
        }

        // Sort
        $('.sort-item').on('click', function () {
            $('#sortBtn').text($(this).text());

            $('[name="sort_type"]').val($(this).data('sort'));

            $projectTable.ajax.reload();
        });

        // Filter By User
        $('#users')
            .select2()
            .on('change', function () {
                $('#myProject').prop('checked', false);

                $projectTable.ajax.reload();
            });

        // Filter By Project Status
        $('#projectStatus').on('change', function () {
            $projectTable.ajax.reload();
        });

        // Select My Project
        $('#myProject').on('click', function () {
            $projectTable.ajax.reload();
        });

        // Add new row input
        $('#addRowInput').on('click', addNewRowInput);

        // Delete row input
        $(document).on('click', '.delete-row-input', deleteRowInput);

        // Open Modal To Create
        $('#createProject').on('click', openModalToCreate);

        // Open Modal To Edit
        $(document).on('click', '.project-edit', openModalToUpdate);

        // Save Project
        $('#saveProjectForm').on('submit', saveProject);

        // Delete Project
        $(document).on('click', '.project-delete', deleteProject);
    });
})(jQuery, document);
