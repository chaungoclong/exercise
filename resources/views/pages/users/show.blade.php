@extends('layouts.customs.app')

@section('title', 'User View')

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
    <section class="app-user-view-account">
        <div class="row">
            <!-- User Sidebar -->
            <div class="col-4 order-1 order-md-0">
                <!-- User Card -->
                <div class="card">
                    <div class="card-body">
                        <div class="user-avatar-section">
                            <div class="d-flex align-items-center flex-column">
                                <img class="img-fluid rounded mt-3 mb-2"
                                    src="{{ $user->avatar }}"
                                    height="100"
                                    width="100"
                                    alt="User avatar" />
                                <div class="user-info text-center">
                                    <h4>{{ $user->full_name }}</h4>
                                    <span class="badge bg-light-secondary">
                                        {{ optional($user->role)->name }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-around my-2 pt-75">
                            <div class="d-flex align-items-start">
                                <span class="badge bg-light-primary p-75 rounded">
                                    <i data-feather="briefcase"
                                        class="font-medium-2"></i>
                                </span>
                                <div class="ms-75">
                                    <h4 class="mb-0">
                                        {{ $user->projects->count() }}
                                    </h4>
                                    <small>Projects</small>
                                </div>
                            </div>
                        </div>
                        <h4 class="fw-bolder border-bottom pb-50 mb-1">Details</h4>
                        <div class="info-container">
                            <ul class="list-unstyled">
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Username:</span>
                                    <span>{{ $user->username }}</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Email:</span>
                                    <span>{{ $user->email }}</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Status:</span>
                                    <x-active-badge :status="$user->status"></x-active-badge>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Role:</span>
                                    <span>{{ optional($user->role)->name }}</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Address:</span>
                                    <span>{{ $user->address }}</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Phone:</span>
                                    <span>{{ $user->phone }}</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Gender:</span>
                                    <span class="text-capitalize">{{ $user->gender_title }}</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Birthday:</span>
                                    <span>{{ $user->birthday }}</span>
                                </li>
                            </ul>
                            <div class="d-flex justify-content-center pt-2">
                                <a href="javascript:;"
                                    class="btn btn-primary me-1 user-edit"
                                    data-url-edit="{{ route('users.edit', $user) }}"
                                    data-url-update="{{ route('users.update', $user) }}">
                                    Edit
                                </a>
                                <a href="javascript:;"
                                    class="btn btn-outline-danger user-delete"
                                    data-url-delete="{{ route('users.destroy', $user) }}">Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /User Card -->
            </div>
            <!--/ User Sidebar -->

            <!-- User Content -->
            <div class="col-8 order-0 order-md-1">
                <!-- Project table -->
                <div class="card">
                    <h4 class="card-header">User's Projects List</h4>
                    <div class="table-responsive">
                        <table class="table datatable-project">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Project</th>
                                    <th>Status</th>
                                    <th>Revenue</th>
                                    <th>Duration</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($user->projects as $project)
                                    <tr>
                                        <td>
                                            {{ $project->id }}
                                        </td>
                                        <td>
                                            {{ $project->name }}
                                        </td>
                                        <td>

                                            @switch($project->status)
                                                @case(\App\Models\Project::STATUS_ON_HOLD)
                                                    <span class="{{ config('constants.project_status_class.on_hold') }}">On
                                                        Hold</span>
                                                @break

                                                @case(\App\Models\Project::STATUS_IN_PROGRESS)
                                                    <span class="{{ config('constants.project_status_class.in_progress') }}">
                                                        In Progress
                                                    </span>
                                                @break

                                                @case(\App\Models\Project::STATUS_COMPLETED)
                                                    <span class="{{ config('constants.project_status_class.completed') }}">
                                                        Completed
                                                    </span>
                                                @break

                                                @case(\App\Models\Project::STATUS_CANCELLED)
                                                    <span class="{{ config('constants.project_status_class.cancelled') }}">
                                                        Cancelled
                                                    </span>
                                                @break
                                            @endswitch
                                        </td>
                                        <td>
                                            @vnd($project->revenue)
                                        </td>
                                        <td>
                                            {{ $project->duration }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /Project table -->
            </div>
            <!--/ User Content -->
        </div>
    </section>

    {{-- Save User Modal --}}
    <x-modals.save-user></x-modals.save-user>
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
    <x-js name="list_user_url"
        value="{{ route('users.index') }}" />
@endpush

@push('page-script')
    <script src="{{ asset(mix('js/scripts/pages/page-user-show.js')) }}"></script>
@endpush
