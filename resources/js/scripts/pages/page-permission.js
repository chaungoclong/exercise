(function ($, document, window) {
    'use strict';

    $(function () {
        setToken();

        let $showPermissionModal = $('#showPermissionModal');
        let showPermissionModal = new bootstrap.Modal($showPermissionModal[0]);
        let $listPermissionContainer = $('#listPermission');

        // Init DataTables
        let permissionTable = $('#listPermissionTbl').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: $listPermissionContainer.data('url-datatables'),
                data: (d) => {
                    d.search = $('#listPermissionTbl_filter input')
                        .val();
                    d.sort = $('[name="sort_type"]').val();

                    d.role_id = $('#userRole').val();
                }
            },
            columns: [{
                data: 'html'
            }],

            initComplete: function (settings, json) {
                // show new container for data
                $listPermissionContainer
                    .insertBefore('#listPermissionTbl');

                $listPermissionContainer.show();
            },
            rowCallback: function (row, data) {
                $listPermissionContainer.append(data.html);
            },
            preDrawCallback: function (settings) {
                // clear list before draw
                $listPermissionContainer.empty();
            }
        });

        // Show Permission
        const showPermission = function () {
            showPermissionModal.show();

            let url = $(this).data('url-show');

            $.get(url)
                .done((response) => {
                    let permission = response.data.permission;

                    // Render Assigned Role
                    let assignedHtml = permission.roles.map(role => {
                        return `
                        <span class="badge rounded-pill         badge-light-primary mb-1">
                            ${role.name}
                        </span>`;
                    }).join('');

                    // Render Content
                    let html = `
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>Name:</td>
                                <td><span>${permission.name}</span></td>
                            </tr>

                            <tr>
                                <td>Slug:</td>
                                <td><span>${permission.slug}</span></td>
                            </tr>

                            <tr>
                                <td>Created At:</td>
                                <td><span>${permission.created_at}</span></td>
                            </tr>

                            <tr>
                                <td>Assigned:</td>
                                <td>
                                    ${assignedHtml}
                                </td>
                            </tr>
                        </tbody>
                    </table>`;

                    $showPermissionModal
                        .find('.modal-body')
                        .html(html).end()
                        .find('.modal-title')
                        .html(`Permission Details: ${permission.name}`);

                }).fail((xhr) => {
                    showToast(xhr, 'error');
                });
        }

        // Sort
        $('.sort-item').on('click', function () {
            $('[name="sort_type"]').val($(this).data('sort'));

            $('#sortBtn').text($(this).text());

            permissionTable.ajax.reload();
        });

        // Filter By Role
        $('#userRole').on('change', function () {
            console.log($(this).val());
            permissionTable.ajax.reload();
        });


        // Show Permission
        $(document).on('click', '.permission-show', showPermission);
    });
})(jQuery, document, window);
