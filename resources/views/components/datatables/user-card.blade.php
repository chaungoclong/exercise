<div class="col-xl-4 col-lg-6 col-md-6">
    <div class="card">
        <div class="card-header d-flex justify-content-beetween">
            <div class="d-flex justify-content-left align-items-center"
                style="max-width: 80%;">
                {{-- Avatar && Status Wrapper --}}
                <div class="avatar-wrapper mb-1">
                    <div class="avatar me-1 mb-1">
                        <img src="{{ $user->avatar }}"
                            alt="Avatar"
                            height="40"
                            width="40">
                    </div>

                    {{-- Switch Status --}}
                    @if (auth()->id() !== $user->id)
                        <div class="form-check form-switch pb-0">
                            <input type="checkbox"
                                class="form-check-input status-switch"
                                @checked($user->status)
                                @disabled($user->isAdmin() && !$user->is_user_define)
                                data-url-switch="{{ route('users.switch_status', $user) }}">

                        </div>
                    @endif
                </div>
                {{-- /Avatar && Status Wrapper --}}
                {{-- User Info --}}
                <div class="d-flex flex-column align-items-start justify-content-start">
                    {{-- Name --}}
                    <a href="{{ route('users.show', $user) }}"
                        class="user_name text-truncate text-body">
                        <h5 class="fw-bolder text-wrap text-break text-capitalize">
                            {{ $user->full_name }}
                        </h5>
                    </a>

                    <div>
                        {{-- User Role --}}
                        @isset($user->role)
                            @switch($user->role->slug)
                                @case(\App\Models\Role::ADMIN)
                                    <span class="{{ config('constants.role_class.admin') }}">
                                        {{ $user->role->name }}
                                    </span>
                                @break

                                @case(\App\Models\Role::MANAGER)
                                    <span class="{{ config('constants.role_class.manager') }}">
                                        {{ $user->role->name }}
                                    </span>
                                @break

                                @case(\App\Models\Role::EMPLOYEE)
                                    <span class="{{ config('constants.role_class.employee') }}">
                                        {{ $user->role->name }}
                                    </span>
                                @break

                                @default
                                    <span class="{{ config('constants.role_class.user_define') }}">
                                        {{ $user->role->name }}
                                    </span>
                                @break
                            @endswitch
                        @endisset
                        {{-- /User Role --}}

                        @if (auth()->id() === $user->id)
                            <span class="badge rounded-pill badge-light-info">
                                You
                            </span>
                        @endif
                    </div>

                    {{-- Email --}}
                    <p class="mb-0">
                        <small class="emp_post text-muted">
                            {{ $user->email ?? '' }}
                        </small>
                    </p>
                    {{-- /Email --}}

                </div>
                {{-- /User Info --}}
            </div>

            {{-- Button Action --}}
            <div class="btn-group align-self-start">
                <a class="btn btn-sm dropdown-toggle hide-arrow p-0"
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
                    @if (auth()->id() === $user->id)
                        <a class="dropdown-item"
                            href="{{ route('profile.show') }}">
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
                            My Profile
                        </a>
                    @else
                        <a class="dropdown-item user-edit"
                            data-url-edit="{{ route('users.edit', $user) }}"
                            data-url-update="{{ route('users.update', $user) }}">
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

                        <a class="dropdown-item user-delete"
                            data-url-delete="{{ route('users.destroy', $user) }}">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="feather feather-trash">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path
                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                </path>
                            </svg>
                            Delete
                        </a>
                    @endif
                </div>
            </div>
            {{-- /Button Action --}}
        </div>
        <div class="card-body">
            <h4 class="fw-bolder border-bottom pb-50 mb-1">Details</h4>
            <div class="info-container">
                <ul class="list-unstyled">
                    <li class="mb-75">
                        <span class="fw-bolder me-25">Username:</span>
                        <span>{{ $user->username }}</span>
                    </li>
                    <li class="mb-75">
                        <span class="fw-bolder me-25">Status:</span>
                        <x-active-badge :status="$user->status"></x-active-badge>
                    </li>
                    <li class="mb-75">
                        <span class="fw-bolder me-25">Phone:</span>
                        <span>{{ $user->phone }}</span>
                    </li>
                    <li class="mb-75">
                        <span class="fw-bolder me-25">Gender:</span>
                        <span class="text-capitalize">{{ $user->gender_title }}</span>
                    </li>
                </ul>
            </div>
            <div class="d-flex justify-content-between">
                <div class="d-flex align-items-start">
                    <span class="badge bg-light-primary p-75 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            width="14"
                            height="14"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="feather feather-briefcase font-medium-2">
                            <rect x="2"
                                y="7"
                                width="20"
                                height="14"
                                rx="2"
                                ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                    </span>
                    <div class="ms-75">
                        @php
                            $projectOfUser = $user->projectMembers->map(fn($item) => $item->project)->unique();
                        @endphp
                        <h4 class="mb-0">{{ $projectOfUser->count() }}</h4>
                        <small>Project</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
