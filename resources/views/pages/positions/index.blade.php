@extends('layouts.customs.app')

@section('title', 'Manage Positions')

@push('vendor-style')
    <!-- Vendor css files -->
    <link rel="stylesheet"
        href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
    <link rel="stylesheet"
        href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
    <link rel="stylesheet"
        href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap5.min.css')) }}">
    <link rel="stylesheet"
        href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endpush

@push('page-style')
    <link rel="stylesheet"
        href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
@endpush

@section('content')
    <h3>Positions List</h3>
    <p class="mb-2">

    </p>

    {{-- Tool Bar --}}
    <div class="card">
        <div class="card-body d-flex justify-content-between">
            {{-- Sort Button --}}
            <div class="btn-group">
                <button class="btn btn-outline-info dropdown-toggle"
                    type="button"
                    id="sortBtn"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Sort
                </button>

                {{-- Input Save Sort Type For Send To Server --}}
                <input type="hidden"
                    name="sort_type"
                    value="{{ config('constants.sort.latest') }}">

                <div class="dropdown-menu"
                    aria-labelledby="dropdownMenuButton">
                    <li>
                        <a class="dropdown-item sort-item"
                            data-sort="{{ config('constants.sort.latest') }}"
                            href="#">Latest
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item sort-item"
                            data-sort="{{ config('constants.sort.oldest') }}"
                            href="#">
                            Oldest
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item sort-item"
                            data-sort="{{ config('constants.sort.a-z') }}"
                            href="#">
                            A-Z
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item sort-item"
                            data-sort="{{ config('constants.sort.z-a') }}"
                            href="#">
                            Z-A
                        </a>
                    </li>
                </div>
            </div>
            {{-- /Sort Button --}}

            {{-- Create Button --}}
            <div class="btn-group">
                <button class="btn btn-outline-success"
                    type="button"
                    id="createPosition"
                    data-url-create="{{ route('positions.create') }}">
                    New Position
                </button>
            </div>
            {{-- /Create Button --}}
        </div>
    </div>
    {{-- /Tool Bar --}}

    {{-- Table Use For Get Datatables Form Server --}}
    <table id="listPositionTbl"
        style="display: none;"
        cellspacing="0"
        width="100%"></table>


    {{-- Container List Position(Get From '#listPositionTbl') --}}
    <div class="row my-2"
        id="listPosition"
        style="display: none"
        data-url-datatables="{{ route('positions.datatables') }}">
    </div>


    {{-- Create Position Modal --}}
    <x-modals.create-position></x-modals.create-position>

    {{-- Edit Position Modal --}}
    <x-modals.edit-position></x-modals.edit-position>
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
    <script src="{{ asset(mix('js/scripts/pages/page-position.js')) }}"></script>
@endpush
