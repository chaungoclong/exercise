((document, $) => {
    'use strict';

    $(function () {
        setToken();

        const PAGE_ADMIN = 'admin';
        const OPTION_PROJECT = 'project';
        const OPTION_POSITION = 'position';
        const authId = $('#authId').val();

        let isUpdate = false;
        let urlSave = '';

        // Report Datatables
        let $reportTable = $('#reportTable').DataTable({
            dom: `
            <"d-flex justify-content-between align-items-center header-actions text-nowrap row mt-75"
                <"col-sm-12 col-lg-4 d-flex justify-content-center justify-content-lg-start" l>
                <"col-sm-12 col-lg-8"
                    <"dt-action-buttons d-flex align-items-center justify-content-lg-end justify-content-end flex-md-nowrap flex-wrap"
                        <"me-1"f>
                        <"add-btn-wrap mt-50 me-0">
                    >
                >
                ><"text-nowrap" t>
                <"d-flex justify-content-between mx-2 row mb-1"
                <"col-sm-12 col-md-6"i>
                <"col-sm-12 col-md-6"p>
            >`,
            processing: true,
            serverSide: true,
            ajax: {
                url: $('#urlDatatables').val(),
                data: (d) => {
                    d.search = $('#reportTable_filter input').val();
                    d.working_type = $('#workingType').val();
                    d.status = $('#reportStatus').val();
                    d.from_date = $('#reportFromDate').val();
                    d.to_date = $('#reportToDate').val();
                },
                error: (xhr) => {
                    showToast(xhr, 'error');
                }
            },
            order: [[3, 'desc']],
            columns: [
                {
                    data: 'project_id',
                    sortable: false
                },
                {
                    data: 'position_id',
                    sortable: false
                },
                {
                    data: 'working_time',
                    name: 'working_time'
                },
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'working_type',
                    name: 'working_type',
                    sortable: false
                },
                {
                    data: 'status',
                    name: 'status',
                    sortable: false
                },
                {
                    data: 'note',
                    name: 'note'
                },
                {
                    data: 'actions',
                    sortable: false
                }
            ]
        });

        // Init From Date
        let $reportFromDate = $('#reportFromDate').flatpickr({
            altInput: true,
            altFormat: "d-m-Y",
            dateFormat: "Y-m-d",
            maxDate: 'today',
            onChange: function (
                selectedDates,
                dateStr,
                instance
            ) {
                $reportToDate.set('minDate', dateStr);
            }
        });

        // Init To Date
        let $reportToDate = $('#reportToDate').flatpickr({
            altInput: true,
            altFormat: "d-m-Y",
            dateFormat: "Y-m-d",
            maxDate: 'today',
            onChange: function (
                selectedDates,
                dateStr,
                instance
            ) {
                $reportFromDate.set('maxDate', dateStr);
            }
        });

        // Modal and Form Save Report
        let $saveReportModal = $('#saveReportModal');
        let saveReportModal = new bootstrap.Modal($saveReportModal[0]);
        let $saveReportForm = $('#saveReportForm');
        let $saveReportModalLabel = $('#saveReportModalLabel');

        let $projectSelect = $('#project_id');
        let $positionSelect = $('#position_id');
        let $workingTypeSelect = $('#working_type');

        // Date input in form save report
        let $dateInput = $saveReportForm.find('#date')
            .flatpickr({
                altInput: true,
                altFormat: 'd-m-Y',
                maxDate: 'today',
                onReady: function (selectedDates, dateStr, instance) {
                    instance.altInput.name = 'date_alt';
                }
            });

        // Function Filter
        function filter(event) {
            event.preventDefault();

            $reportTable.ajax.reload();
        }

        // Function Reset Date Filter
        function resetFilter() {
            // Reset Date Filter
            $reportFromDate.set('minDate', undefined);
            $reportFromDate.set('maxDate', 'today');
            $reportFromDate.setDate(undefined);

            $reportToDate.set('minDate', undefined);
            $reportToDate.set('maxDate', 'today');
            $reportToDate.setDate(undefined);

            // Reset Select Field
            $('#reportStatus').val('').trigger('change');
            $('#workingType').val('').trigger('change');
        };

        // Function load position when project select is changed
        function loadPositionSelect() {
            let url = $('#urlGetOptions').val();

            $.get(url, {
                user_id: authId,
                project_id: $projectSelect.val(),
                type: OPTION_POSITION
            }).done((response) => {
                $positionSelect
                    .wrap('<div class="position-relative"></div>')
                    .empty()
                    .select2({
                        dropdownParent: $saveReportModal,
                        placeholder: 'Select Position',
                        data: response.data.options
                    })
                    .val('')
                    .trigger('change.select2');
            });
        };

        // Function open modal
        function openModal() {
            // Reset form
            resetForm($saveReportForm);

            // Show Modal
            saveReportModal.show();

            // Reset Date Picker
            $dateInput.setDate(null);

            // Refresh position select
            $positionSelect
                .wrap('<div class="position-relative"></div>')
                .empty()
                .select2({
                    dropdownParent: $saveReportModal,
                    placeholder: 'Select Position'
                });

            // Refresh working type select (not use empty() because static data)
            $workingTypeSelect
                .wrap('<div class="position-relative"></div>')
                .select2({
                    dropdownParent: $saveReportModal,
                    placeholder: 'Select Working Type'
                });
        }

        // Function close modal
        function closeModal() {
            // Reset form
            resetForm($saveReportForm);

            // Hide modal
            saveReportModal.hide();
        }

        // Function open modal to create
        function openModalCreate() {
            // Open modal
            openModal();

            isUpdate = false;

            // Set modal label
            $saveReportModalLabel.text('Create Report');

            // Set url save = store
            urlSave = $('#urlStore').val();

            // Load data for create
            let url = $('#urlCreate').val();

            $.get(url)
                .done((response) => {
                    // Init project select
                    $projectSelect
                        .wrap('<div class="position-relative"></div>')
                        .select2({
                            dropdownParent: $saveReportModal,
                            placeholder: 'Select Project',
                            data: response.data.projectOptions
                        });
                })
                .fail((xhr) => {
                    showToast(xhr, 'error');
                });
        }

        // Function open modal edit
        function openModalEdit() {
            // Open modal
            openModal();

            isUpdate = true;

            // Set modal label
            $saveReportModalLabel.text('Update Report');

            // Set url save = update
            urlSave = $(this).data('url-update');

            // Load data for edit
            let url = $(this).data('url-edit');

            $.get(url)
                .done((response) => {
                    let data = response.data;
                    let report = data.report;
                    let projectOptions = data.projectOptions;
                    let positionOptions = data.positionOptions;

                    // Bind data to form
                    $saveReportForm.jsonToForm(report, {
                        // Init project select and set option selected
                        project_id: (value) => {
                            $projectSelect
                                .select2({
                                    dropdownParent: $saveReportModal,
                                    placeholder: 'Select Project',
                                    data: projectOptions
                                })
                                .val(value)
                                .trigger('change.select2');
                        },
                        // Init position select and set option selected
                        position_id: (value) => {
                            $positionSelect
                                .wrap('<div class="position-relative"></div>')
                                .select2({
                                    dropdownParent: $saveReportModal,
                                    placeholder: 'Select Position',
                                    data: positionOptions
                                })
                                .val(value)
                                .trigger('change.select2');
                        },
                        // Init working type select and set option selected
                        working_type: (value) => {
                            $workingTypeSelect
                                .wrap('<div class="position-relative"></div>')
                                .select2({
                                    dropdownParent: $saveReportModal,
                                    placeholder: 'Select Working Type',
                                })
                                .val(value)
                                .trigger('change.select2');
                        },
                        // Init date picker and set date
                        date: (value) => {
                            $dateInput.setDate(value, true, 'd-m-Y');
                        },
                    });
                }).fail((xhr) => {
                    showToast(xhr, 'error');
                });
        }

        // Function Save Report
        function saveReport(event) {
            event.preventDefault();

            let data = $saveReportForm.serialize();
            let method = isUpdate ? 'PUT' : 'POST';

            if ($saveReportForm.valid()) {
                $.ajax({
                    url: urlSave,
                    method: method,
                    data: data,
                    dataType: 'JSON'
                }).done((response) => {
                    // Reload go to first page when store
                    // Reload current page when update
                    if (!isUpdate) {
                        $reportTable.ajax.reload();
                    } else {
                        $reportTable.ajax.reload(null, false);
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

        // Function Delete Report
        function deleteReport() {
            Swal.fire({
                title: 'Do You Want Delete This Report',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'No!',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = $(this).data('url-delete');

                    $.ajax({
                        url: url,
                        method: 'DELETE',
                        dataType: 'JSON'
                    }).done((response) => {
                        $reportTable.ajax.reload(null, false);

                        showToast(response);
                    }).fail((xhr) => {
                        showToast(xhr, 'error');
                    });
                }
            });
        }

        // Function Approve Report
        function approveReport() {
            Swal.fire({
                title: 'Do You Want Approve This Report',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'No!',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = $(this).data('url-approve');

                    $.ajax({
                        url: url,
                        method: 'PATCH',
                        dataType: 'JSON'
                    }).done((response) => {
                        $reportTable.ajax.reload(null, false);

                        showToast(response);
                    }).fail((xhr) => {
                        showToast(xhr, 'error');
                    });
                }
            });
        }

        // Init button create
        $(`<button class="btn btn-outline-success"
            id="createReport">Add</button>`)
            .appendTo('.add-btn-wrap');

        // Init Select 2 for all filter select
        $('.filter-select').select2();

        // Filter
        $('#filter').on('click', filter);

        // Reset Date Filter
        $('#resetFilter').on('click', resetFilter);

        // Validate form
        $saveReportForm.validate({
            rules: {
                project_id: {
                    required: true
                },
                position_id: {
                    required: true
                },
                working_type: {
                    required: true
                },
                working_time: {
                    required: true,
                    max: 8
                },
                date_alt: {
                    required: true,
                },
                note: {
                    required: true
                },
            }
        });

        // When project select is changed
        $projectSelect.on('change', function () {
            // Validate
            $(this).valid();

            // Load position select
            loadPositionSelect();
        });

        // Validate(position select, working type select) when is changed
        $positionSelect.add($workingTypeSelect)
            .on('change', function () {
                $(this).valid();
            });

        // Open Modal For Create
        $('#createReport').on('click', openModalCreate);

        // Open Modal For Edit
        $(document).on('click', '.report-edit', openModalEdit);

        // Save Report
        $saveReportForm.on('submit', saveReport);

        // Delete Report
        $(document).on('click', '.report-delete', deleteReport);

        // Approve Report
        $(document).on('click', '.report-approve', approveReport);
    });
})(document, jQuery);
