@extends('layouts.customs.app')

@section('title', 'Manage Roles')

@push('vendor-style')
<!-- Vendor css files -->
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap5.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endpush

@push('page-style')
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
@endpush

@section('content')
<div class="">
    <div class="card d-flex flex-row justify-content-between container py-1">
        <button id="test" class="btn btn-info" style="width: 100px;">Test</button>

        <div class="btn-group">
            <button class="btn btn-outline-info dropdown-toggle" type="button" id="sortBtn" data-bs-toggle="dropdown"
                aria-expanded="false">
                Sort
            </button>
            <input type="hidden" name="sort_type" value="1">

            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li>
                    <a class="dropdown-item sort-item" data-sort="{{ config('constants.sort.latest') }}" href="#">Latest
                    </a>
                </li>

                <li>
                    <a class="dropdown-item sort-item" data-sort="{{ config('constants.sort.oldest') }}" href="#">
                        Oldest
                    </a>
                </li>

                <li>
                    <a class="dropdown-item sort-item" data-sort="{{ config('constants.sort.a-z') }}" href="#">
                        A-Z
                    </a>
                </li>

                <li>
                    <a class="dropdown-item sort-item" data-sort="{{ config('constants.sort.z-a') }}" href="#">
                        Z-A
                    </a>
                </li>
            </ul>
        </div>

        <button id="add" class="btn btn-info" style="width: 100px;">Add</button>
    </div>

    <table id="example" class="display" style="display: none;" cellspacing="0" width="100%"></table>
    <div id="new-list" class="row my-1" style="display: none;"></div>
</div>
@endsection

@push('vendor-script')
<script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.bootstrap5.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap5.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/buttons.bootstrap5.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/datatables.checkboxes.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@endpush

@push('page-script')
<script>
    $(function() {
      let table = $('#example').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true,
        ajax: {
          url: '{{ route('roles.userdatatables') }}',
          data: (d) => {
            d.search = $('#example_filter input').val();
            d.sort = $('[name="sort_type"]').val();
          }
        },
        columns: [{
          data: 'html'
        }],
        "initComplete": function(settings, json) {
          // show new container for data
          $('#new-list').insertBefore('#example');
          //   alert('here');
          $('#new-list').show();
        },
        "rowCallback": function(row, role) {
          let $roleCard = $();

          $('#new-list').append(role.html);
        },
        "preDrawCallback": function(settings) {
          // clear list before draw
          $('#new-list').empty();
        }
      });

      $('#test').on('click', function() {
        console.log(table);
        table.ajax.reload(null, false);
      });

      $('#example_filter input').on('keyup', function() {
        console.log(table);
        table.ajax.reload();
      });

      $('.sort-item').on('click', function() {
        $('[name="sort_type"]').val($(this).data('sort'));
        $('#sortBtn').text($(this).text());
        table.ajax.reload();
      });
    });
</script>
@endpush
