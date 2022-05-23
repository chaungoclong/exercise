@extends('layouts.customs.app')
@section('title', 'Project Statistics')
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
    <div class="row"
        id="userWorkStatistic">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title">Statistic Working Time</div>
                    <form action=""
                        method="GET"
                        id="formFilter">
                        <div class="d-flex justify-content-end align-items-start">
                            {{-- Filter by Project --}}
                            <div class="me-1">
                                <select id="projectSelect"
                                    name="project_id"
                                    class="form-select"
                                    value="2">
                                    <option value=""></option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}">
                                            {{ $project->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Filter by Time --}}
                            <div class="me-1">
                                <input type="text"
                                    class="form-control date-range"
                                    style="min-width: 300px;"
                                    id="dateRange"
                                    placeholder="DD-MM-YYYY"
                                    readonly="readonly">

                                {{-- Storage date filter --}}
                                <input type="hidden"
                                    name="from_date"
                                    value="{{ now()->firstOfMonth()->toDateString() }}"
                                    id="from_date">

                                <input type="hidden"
                                    name="to_date"
                                    value="{{ now()->toDateString() }}"
                                    id="to_date">
                            </div>
                            <div>
                                <button class="btn btn-outline-primary">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body">
                    {{-- Working Time By Type --}}
                    <div class="row mb-2">
                        <div class="col-12">
                            <div>
                                <canvas id="workingTimeByPositionChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table project-members-table"
                                    id="projectMembersTbl">
                                    <thead>
                                        <th>User</th>
                                        <th>Offsite</th>
                                        <th>Onsite</th>
                                        <th>Remote</th>
                                        <th>Off</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($projectUsers as $user)
                                            <tr>
                                                <td>
                                                    <x-cards.user-td-statistic :user="$user">
                                                    </x-cards.user-td-statistic>
                                                </td>
                                                <td>
                                                    {{ $user['workingTimes']['Offsite'] ?? 0 }}
                                                </td>
                                                <td>
                                                    {{ $user['workingTimes']['Onsite'] ?? 0 }}
                                                </td>
                                                <td>
                                                    {{ $user['workingTimes']['Remote'] ?? 0 }}
                                                </td>
                                                <td>
                                                    {{ $user['workingTimes']['Off'] ?? 0 }}
                                                </td>
                                                <td>
                                                    {{ array_sum($user['workingTimes']) }}
                                                </td>
                                                <td>
                                                    <a class="btn btn-outline-success btn-sm"
                                                        href="{{ route('statistics.user', $user['id']) . '?project_id=' . request('project_id', '') }}">
                                                        View Statistic
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('phpToJs')
    <input type="hidden"
        id="firstOfMonth"
        value="{{ now()->firstOfMonth()->toDateString() }}">

    <input type="hidden"
        id="now"
        value="{{ now()->toDateString() }}">

    <input type="hidden"
        id="workingTimesByPosition"
        value="{{ json_encode($workingTimesByPosition ?? null) }}">

    <input type="hidden"
        id="workingTimeByProject"
        value="{{ json_encode($workingTimeByProject ?? null) }}">

    <input type="hidden"
        id="oldProjectId"
        value="{{ request('project_id', '') }}">

    <input type="hidden"
        id="oldFromDate"
        value="{{ request('from_date', '') }}">

    <input type="hidden"
        id="oldToDate"
        value="{{ request('to_date', '') }}">
@endpush

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
    <script src="{{ asset(mix('js/scripts/pages/page-statistis-project.js')) }}"></script>
@endpush
