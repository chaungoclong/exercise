((document, $) => {
    'use strict';

    $(function () {
        setToken();

        const authId = $('#authId').val();
        const ACTION_STORE = 'store';
        const ACTION_UPDATE = 'update';

        let _url = '';
        let _method = 'POST';
        let _action = ACTION_STORE;


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
                }
            },
            columns: [{
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

        let $projectSelect = $('#project_id');
        let $positionSelect = $('#position_id');
        let $workingType = $('#working_type');

        // Date input in form save report
        let $dateInput = $saveReportForm.find('#date').flatpickr({
            altInput: true,
            altFormat: 'd-m-Y',
            maxDate: 'today'
        });


        // Function Filter
        const filter = function (event) {
            event.preventDefault();

            $reportTable.ajax.reload();
        }

        // Function Reset Date Filter
        const resetFilter = function () {
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

        // Function Open Modal
        const openModal = () => {
            resetForm($saveReportForm);

            saveReportModal.show();

            // Clear all content in position select when open Modal then init
            $positionSelect.empty().select2({
                placeholder: 'Select Position'
            });

            // Init Working type select
            $workingType.select2({
                placeholder: 'Select Working Type'
            });

            // Clear date selected
            $dateInput.setDate(undefined);
        };

        // Function Close Modal
        const closeModal = () => {
            resetForm($saveReportForm);

            saveReportModal.hide();
        };

        // Function Open Modal To Create
        const openModalCreate = function () {
            openModal();

            _url = $('#url_create').val();
            _method = 'POST';
            _action = ACTION_STORE;

            $.get(_url)
                .done((response) => {
                    $projectSelect.select2({
                        placeholder: 'Select Project',
                        data: response.data.projectOptions
                    })
                        .on('change', function () {
                            $(this).valid();
                        });

                    // Set URL store
                    _url = $('#urlStore').val();
                }).fail((xhr) => {
                    showToast(xhr, error);
                });
        };

        // Function Open Modal To Edit
        const openModalEdit = function () {
            _url = $(this).data('url-edit');
            _method = 'PUT';
            _action = ACTION_UPDATE;

            $.get(_url)
                .done((response) => {
                    openModal();

                    let report = response.data.report;
                    let projectOptions = response.data.projectOptions;
                    let positionOptions = response.data.positionOptions;

                    // Bind Report to Form
                    $saveReportForm.jsonToForm(report, {
                        // Init and selected Project select
                        project_id: (value) => {
                            $projectSelect.select2({
                                placeholder: 'Select Project',
                                data: projectOptions
                            })
                                .on('change', function () {
                                    $(this).valid();
                                })
                                .val(report.project_id)
                                .trigger('change.select2');
                        },
                        // Init and selected Position select
                        position_id: (value) => {
                            $positionSelect.select2({
                                placeholder: 'Select Position',
                                data: positionOptions
                            }).on('change', function () {
                                $(this).valid();
                            })
                                .val(report.position_id)
                                .trigger('change');
                        },
                        // Set Working Type
                        working_type: (value) => {
                            $workingType
                                .select2()
                                .val(report.working_type)
                                .trigger('change');
                        },
                        // Set Date
                        date: (value) => {
                            $dateInput.setDate(value, true, 'd-m-Y');
                        }
                    });

                    // Set URL Update
                    _url = $(this).data('url-update');
                }).fail((xhr) => {
                    showFirstError(xhr, 'error');
                });
        }

        // Function get Position When selected Project
        const getPositionOptions = function () {
            let projectId = $(this).val();

            let urlGetOptions = $('#urlGetOptions').val();

            $.get(urlGetOptions, {
                user_id: authId,
                project_id: projectId,
                type: 'position'
            })
                .done((response) => {
                    $positionSelect
                        .empty()
                        .select2({
                            placeholder: 'Select Position',
                            data: response.data.options
                        }).on('change', function () {
                            $(this).valid();
                        })
                        .val('')
                        .trigger('change');
                }).fail((xhr) => {
                    showToast(xhr, 'error');
                })
        }

        // Function Save Report
        const saveReport = function (event) {
            event.preventDefault();

            let data = $saveReportForm.serialize();

            $.ajax({
                url: _url,
                method: _method,
                data: data,
                dataType: 'JSON'
            }).done((response) => {
                // Reload go to first page when store
                // Reload current page when update
                if (_action === ACTION_STORE) {
                    $reportTable.ajax.reload();
                } else {
                    $reportTable.ajax.reload(null, false);
                }

                closeModal();

                $dateInput.setDate(undefined);

                showToast(response);
            }).fail((xhr) => {
                if (xhr.status === 422) {
                    showFirstError(xhr);
                } else {
                    showToast(xhr, 'error');
                }
            });
        }

        // Function Delete Report
        const deleteReport = function () {
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
                    _url = $(this).data('url-delete');

                    $.ajax({
                        url: _url,
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
        const approveReport = function () {
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
                    _url = $(this).data('url-approve');

                    $.ajax({
                        url: _url,
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
        $(`<button class="btn btn-outline-success"    id="createReport">Add</button>`)
            .appendTo('.add-btn-wrap');

        // Init Select 2 for all select tag
        $('.filter-select').select2();

        // Filter
        $('#filter').on('click', filter);

        // Reset Date Filter
        $('#resetFilter').on('click', resetFilter);

        // Get Position when Select Project
        $projectSelect.on('change', getPositionOptions);

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
