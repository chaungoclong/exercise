@extends('layouts.customs.app')

@section('title', 'Manage Project')

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
    <link rel="stylesheet"
        href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endpush

@push('page-style')
    <link rel="stylesheet"
        href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet"
        href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
@endpush

@section('content')
    <h3>Project List</h3>
    <p class="mb-2">

    </p>

    {{-- Tool Bar --}}
    <div class="card">
        <div class="card-body d-flex justify-content-between">
            <div class="d-flex justify-content-start">
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

                {{-- Filter By User --}}
                <div class="users width-200 ms-1">
                    <select id="users"
                        class="form-select text-capitalize select-2">
                        <option value="">Select User</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{-- /Filter By User --}}

                {{-- Filter By Status --}}
                <div class="project-status width-200 ms-1">
                    <select id="projectStatus"
                        class="form-select text-capitalize select-2">
                        <option value="">Select Project Status</option>

                        @foreach (config('constants.project_status') as $key => $statusConfig)
                            <option value="{{ $key }}">
                                {{ $statusConfig['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{-- /Filter By Status --}}
            </div>

            {{-- Create Button --}}
            <div class="btn-group">
                <button class="btn btn-outline-success"
                    type="button"
                    id="createProject"
                    data-url-create="{{ route('projects.create') }}"
                    data-url-store="{{ route('projects.store') }}">
                    New Project
                </button>
            </div>
            {{-- /Create Button --}}
        </div>
    </div>
    {{-- /Tool Bar --}}

    <div class="row">
        <div class="col-12">
            <label for="myProject">My Project</label>
            <div class="form-check form-switch form-check-primary">
                <input type="checkbox"
                    class="form-check-input"
                    id="myProject"
                    value="1" />
                <label class="form-check-label"
                    for="myProject">
                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                </label>
            </div>
        </div>
    </div>

    {{-- Table Use For Get Datatables Form Server --}}
    <table id="projectTbl"
        style="display: none;"
        cellspacing="0"
        width="100%"></table>


    {{-- Container List Position(Get From '#listPositionTbl') --}}
    <div class="row my-2"
        id="projectList"
        style="display: none"
        data-url-datatables="{{ route('projects.datatables') }}">
    </div>

    {{-- Save Project Modal --}}
    <x-modals.save-project></x-modals.save-project>
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
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
@endpush

@push('page-script')
    <script src="{{ asset(mix('js/scripts/pages/page-project.js')) }}"></script>
@endpush
