@extends('layouts.customs.app')
@section('title', 'My Report')
@push('vendor-style')
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
    <h3>Report List</h3>

    {{-- Report Table --}}
    <div class="card">
        <div class="card-body">
            <span class="card-title">Filter</span>
            <form action=""
                class="border p-1 rounded">
                <div class="row mb-1">
                    {{-- Filter By Working Type --}}
                    <div class="col-3 working-type mb-1">
                        <label class="form-label"
                            for="workingType">Working Type</label>
                        <select id="workingType"
                            class="form-select text-capitalize mb-md-0 mb-2 select2 filter-select">
                            <option value=""> Select Working Type </option>
                            @foreach (config('constants.working_type') as $key => $type)
                                <option value="{{ $key }}">{{ $type['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter By Status --}}
                    <div class="col-3 report-status mb-1">
                        <label class="form-label"
                            for="reportStatus">Report Status</label>
                        <select id="reportStatus"
                            class="form-select text-capitalize mb-md-0 mb-2 select2 filter-select">
                            <option value=""> Select Status </option>
                            @foreach (config('constants.report_status') as $key => $status)
                                <option value="{{ $key }}">{{ $status['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter By Date Range --}}
                    <div class="col-3 report-from-date mb-1">
                        <label class="form-label"
                            for="reportFromDate">From Date</label>
                        <input type="text"
                            id="reportFromDate"
                            class="flatpickr form-select"
                            placeholder="dd-mm-yyyyy">
                    </div>

                    <div class="col-3 report-date mb-1">
                        <label class="form-label"
                            for="reportToDate">To Date</label>
                        <input type="text"
                            id="reportToDate"
                            class="flatpickr form-select"
                            placeholder="dd-mm-yyyyy">
                    </div>
                </div>

                {{-- Filter Button --}}
                <div class="row">
                    <div class="col-12">
                        <button class="btn btn-outline-primary"
                            id="filter">Filter</button>

                        <button class="btn btn-outline-warning"
                            id="resetFilter"
                            type="reset">Reset</button>
                    </div>
                </div>
            </form>

            <div class="card-datatable table-responsive">
                <table class="datatables-reports table"
                    id="reportTable">
                    <thead class="table-light">
                        <tr>
                            <th>Project</th>
                            <th>Position</th>
                            <th>Time</th>
                            <th>Day</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Note</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    {{-- /Report Table --}}

    {{-- Save Report Modal --}}
    <x-modals.save-report></x-modals.save-report>
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

@push('phpToJs')
    {{-- URL Create --}}
    <input type="hidden"
        id="urlCreate"
        value="{{ route('reports.create') }}">

    {{-- URL Store --}}
    <input type="hidden"
        id="urlStore"
        value="{{ route('reports.store') }}">

    {{-- URL Datatables --}}
    <input type="hidden"
        id="urlDatatables"
        value="{{ route('reports.datatables') }}">

    {{-- Auth ID --}}
    <input type="hidden"
        id="authId"
        value="{{ auth()->id() }}">

    {{-- URL Get Options --}}
    <input type="hidden"
        id="urlGetOptions"
        value="{{ route('reports.get_select_options') }}">
@endpush

@push('page-script')
    <script src="{{ asset(mix('js/scripts/pages/page-report.js')) }}"></script>
@endpush
