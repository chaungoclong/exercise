(function ($, document, window) {
    'use strict';

    $(function () {
        setToken();

        // List Position Was Rendered From DataTables
        let $listPositionContainer = $('#listPosition');

        // Modal && Form Create
        let $createPositionModal = $('#createPositionModal');
        let createPositionModal = new bootstrap.Modal($createPositionModal[0]);
        let $createPositionForm = $('#createPositionForm');

        // Modal && Form Edit
        let $editPositionModal = $('#editPositionModal');
        let editPositionModal = new bootstrap.Modal($editPositionModal[0]);
        let $editPositionForm = $('#editPositionForm');

        // DataTables
        let $positionTable = $('#listPositionTbl').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: $listPositionContainer.data('url-datatables'),
                data: (d) => {
                    d.search = $('#listPositionTbl_filter input')
                        .val();
                    d.sort = $('[name="sort_type"]').val();
                }
            },
            columns: [{
                data: 'html'
            }],

            initComplete: function (settings, json) {
                // show new container for data
                $listPositionContainer
                    .insertBefore('#listPositionTbl');

                $listPositionContainer.show();
            },
            rowCallback: function (row, data) {
                $listPositionContainer.append(data.html);
            },
            preDrawCallback: function (settings) {
                // clear list before draw
                $listPositionContainer.empty();
            }
        });

        // Open Create Position Modal Function
        const openCreateModal = function () {
            resetForm($createPositionForm);
            createPositionModal.show();
        };

        // Create New Position Function
        const createPosition = function (event) {
            event.preventDefault();

            let url = this.action;
            let data = $createPositionForm.serializeArray();

            if ($createPositionForm.valid()) {
                $.post(url, data, 'JSON')
                    .done((response) => {
                        $positionTable.ajax.reload();

                        createPositionModal.hide();

                        resetForm($createPositionForm);

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

        // Open Edit Modal Function
        const openEditModal = function () {
            resetForm($editPositionForm);
            editPositionModal.show();

            let $this = $(this);
            let url = $this.data('url-edit');
            $editPositionForm.attr('action', $this.data('url-update'));

            $.get(url)
                .done((response) => {
                    let position = response.data.position;

                    // Bind Data To Form
                    $editPositionForm.find('#name')
                        .val(position.name);

                    $editPositionForm.find('#slug')
                        .val(position.slug)
                        .prop('disabled', true);
                }).fail((xhr) => {
                    showToast(xhr, 'error');
                });
        };

        // Edit Position Function
        const editPosition = function (event) {
            event.preventDefault();

            let url = this.action;

            let data = $editPositionForm.serializeArray();

            if ($editPositionForm.valid()) {
                $.ajax({
                    url: url,
                    method: 'PUT',
                    data: data,
                    dataType: 'JSON'
                }).done((response) => {
                    $positionTable.ajax.reload(null, false);

                    editPositionModal.hide();

                    resetForm($editPositionForm);

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

        // Delete Position Function
        const deletePosition = function () {
            Swal.fire({
                title: 'Bạn có muốn xóa Vị trí này',
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
                        $positionTable.ajax.reload(null, false);

                        showToast(response);
                    }).fail((xhr) => {
                        showToast(xhr, 'error');
                    });
                }
            });
        };

        // Validate Form
        $('#createPositionForm, #editPositionForm').each(function () {
            $(this).validate({
                rules: {
                    name: {
                        required: true,
                    },
                    slug: {
                        required: true
                    }
                }
            });
        });


        // Sort
        $('.sort-item').on('click', function () {
            $('[name="sort_type"]').val($(this).data('sort'));

            $('#sortBtn').text($(this).text());

            $positionTable.ajax.reload();
        });

        // Open Modal Create
        $('#createPosition').on('click', openCreateModal);

        // Create New Position
        $createPositionForm.on('submit', createPosition);

        // Open Edit Modal
        $(document).on('click', '.position-edit', openEditModal);

        // Edit Position
        $editPositionForm.on('submit', editPosition);

        // Delete Position
        $(document).on('click', '.position-delete', deletePosition);
    });
})(jQuery, document, window);
