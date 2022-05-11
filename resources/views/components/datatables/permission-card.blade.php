<div class="col-xl-4 col-lg-6 col-md-6 permission-card mb-2">
    <div class="card h-100">
        <div class="card-header d-flex justify-content-between">
            <div class=""
                style="width: 190px !important">
                <h4 class="fw-bolder text-primary">
                    {{ $permission->name }}
                </h4>
                <strong>({{ $permission->slug }})</strong>
            </div>

            <div class="btn-group">
                <a class="btn btn-sm dropdown-toggle hide-arrow"
                    data-bs-toggle="dropdown">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="feather feather-more-vertical font-small-4">
                        <circle cx="12"
                            cy="12"
                            r="1"></circle>
                        <circle cx="12"
                            cy="5"
                            r="1"></circle>
                        <circle cx="12"
                            cy="19"
                            r="1"></circle>
                    </svg>
                </a>

                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item permission-show"
                        data-url-show="{{ route('permissions.show', $permission) }}">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="feather feather-file-text">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16"
                                y1="13"
                                x2="8"
                                y2="13"></line>
                            <line x1="16"
                                y1="17"
                                x2="8"
                                y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        Detail
                    </a>
                </div>
            </div>

        </div>

        <div class="card-body">
            <div class="">
                <p>Total {{ $permission->roles_count }} roles</p>
                <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                    {{-- List Role --}}
                    @foreach ($permission->roles as $key => $role)
                        @if ($key > 5)
                            <li data-bs-toggle="tooltip"
                                data-popup="tooltip-custom"
                                data-bs-placement="top"
                                title="{{ $role->name }}"
                                class="badge rounded-pill badge-light-primary"
                                style="margin: 1px">
                                <small>
                                    +{{ $permission->roles_count - $key }}
                                </small>
                            </li>
                        @break

                    @else
                        <li data-bs-toggle="tooltip"
                            data-popup="tooltip-custom"
                            data-bs-placement="top"
                            title="{{ $role->name }}"
                            class="badge rounded-pill badge-light-success"
                            style="margin: 1px">
                            <small>{{ $role->name }}</small>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</div>
</div>
