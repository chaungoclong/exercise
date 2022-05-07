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
    <h3>Roles List</h3>
    <p class="mb-2">
        A role provided access to predefined menus and features so that depending <br />
        on assigned role an administrator can have access to what he need
    </p>

    {{-- Tool Bar --}}
    <div class="card">
        <div class="card-body d-flex justify-content-between">
            <div class="btn-group">
                <button class="btn btn-outline-info dropdown-toggle" type="button" id="sortBtn" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Sort
                </button>

                {{-- Input Save Sort Type For Send To Server --}}
                <input type="hidden" name="sort_type" value="{{ config('constants.sort.latest') }}">

                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li>
                        <a class="dropdown-item sort-item" data-sort="{{ config('constants.sort.latest') }}"
                            href="#">Latest
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
                </div>
            </div>

            <!-- Example single danger button -->
            <div class="btn-group">
                <button type="button" class="btn btn-outline-success" data-bs-toggle="dropdown" aria-expanded="false"
                    id="createRole" data-url-create="{{ route('roles.create') }}">
                    New Role
                </button>
            </div>
        </div>
    </div>
    {{-- /Tool Bar --}}

    {{-- Overview: I use DataTables to get paginated data from the Server. Then, when the DataTables are initialized, I will
    show the container where the Roles list will be and insert it in front of the table so that the Roles list
    above the pagination bar. I use the "rowCallback" property to override the display of table rows (show rows as
    card). I set the value of this property with a callback that converts the data returned from the Data Table to a tag.
    The card has just been created
    will be added to the bottom of the container. Here I have rendered the html on the server. Before redrawing the table I
    will empty the container with the "preDrawCallback" property. --}}

    {{-- Table Use For Get Datatables Form Server --}}
    <table id="listRoleTbl" style="display: none;" cellspacing="0" width="100%"></table>


    {{-- Container List Role(Get From '#listRoleTable') --}}
    <div class="row my-2" id="listRole" style="display: none"
        data-url-datatables="{{ route('roles.datatables') }}"></div>
    {{-- /List Role Grid --}}

    {{-- Modal Create Role --}}
    <x-modals.create-role></x-modals.create-role>
    {{-- /Modal Create Role --}}

    {{-- Modal Edit Role --}}
    <x-modals.edit-role></x-modals.edit-role>
    {{-- / Modal Edit Role --}}
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
    <script src="{{ asset(mix('js/scripts/pages/page-roles.js')) }}"></script>
@endpush
