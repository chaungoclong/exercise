<div class="col-xl-4 col-lg-6 col-md-6 role-card mb-2"
    id="role_card_{{ $role->id }}">
    <div class="card h-100">
        <div class="card-header d-flex justify-content-between">
            <div class=""
                style="width: 190px !important">
                <h4 class="fw-bolder text-primary">
                    {{ $role->name }}
                    @if (!$role->is_user_define)
                        <strong class="text-danger">
                            (<small class="text-danger">ROOT</small>)
                        </strong>
                    @endif
                </h4>
                <strong>({{ $role->slug }})</strong>
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
                    <a class="dropdown-item role-edit"
                        data-url-edit="{{ route('roles.edit', $role) }}"
                        data-url-update="{{ route('roles.update', $role) }}">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="feather feather-edit">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        Edit
                    </a>

                    <a class="dropdown-item role-delete"
                        data-url-delete="{{ route('roles.destroy', $role) }}">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="feather feather-trash-2 font-small-4 me-50">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                            </path>
                            <line x1="10"
                                y1="11"
                                x2="10"
                                y2="17"></line>
                            <line x1="14"
                                y1="11"
                                x2="14"
                                y2="17"></line>
                        </svg>
                        Delete
                    </a>
                </div>
            </div>

        </div>

        <div class="card-body">
            <div class="">
                <p>Total {{ $role->users_count }} users</p>
                <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                    {{-- List User's Avatar --}}
                    @foreach ($role->users as $key => $user)
                        @if ($key > 5)
                            <li data-bs-toggle="tooltip"
                                data-popup="tooltip-custom"
                                data-bs-placement="top"
                                title="{{ $user->full_name }}"
                                class="avatar avatar-sm pull-up"
                                style="margin: 1px">
                                <div class="circle-around d-flex justify-content-center align-items-center"
                                    style="width: 2em; height: 2em;">
                                    <small>+{{ $role->users_count - $key }}</small>
                                </div>
                            </li>
                        @else
                            <li data-bs-toggle="tooltip"
                                data-popup="tooltip-custom"
                                data-bs-placement="top"
                                title="{{ $user->full_name }}"
                                class="avatar avatar-sm pull-up"
                                style="margin: 1px">
                                <img class="rounded-circle"
                                    src="{{ $user->avatar ?? asset('images/avatars/2.png') }}"
                                    alt="Avatar" />
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
