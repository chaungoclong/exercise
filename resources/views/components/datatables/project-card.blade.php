<div class="col-xl-4 col-lg-6 col-md-6 role-card mb-2">
    <div class="card h-100">
        <div class="card-header d-flex justify-content-between pb-0">
            <div class="card-title w-75">
                {{-- Name --}}
                <a href="{{ route('projects.show', $project) }}"
                    class="text-capitalize">
                    {{ $project->name }}
                </a>

                {{-- Slug --}}
                <p>
                    <small class="text-info">({{ $project->slug }})</small>
                </p>

                {{-- Badge Status --}}
                <div>
                    <span class="{{ config("constants.project_status.$project->status.badge") }}">
                        {{ config("constants.project_status.$project->status.name") }}
                    </span>
                </div>
            </div>

            {{-- Button More Action --}}
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
                    <a class="dropdown-item project-edit"
                        data-url-edit="{{ route('projects.edit', $project) }}"
                        data-url-update="{{ route('projects.update', $project) }}">
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

                    <a class="dropdown-item project-delete"
                        data-url-delete="{{ route('projects.destroy', $project) }}">
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
            {{-- /Button More Action --}}
        </div>

        <div class="dropdown-divider"></div>

        <div class="card-body">
            <div class="">
                <ul class="list-unstyled">
                    <li class="mb-75">
                        <span class="fw-bolder me-25">Bắt đầu lúc:</span>
                        <span>{{ $project->start_date_text }}</span>
                    </li>
                    <li class="mb-75">
                        <span class="fw-bolder me-25">Deadline:</span>
                        <span>{{ $project->deadline_text }}</span>
                    </li>
                    <li class="mb-75">
                        <span class="fw-bolder me-25">Thời lượng:</span>
                        <span>{{ $project->duration }} giờ</span>
                    </li>
                    <li class="mb-75">
                        <span class="fw-bolder me-25">Doanh thu:</span>
                        <span>@vnd($project->revenue)</span>
                    </li>
                </ul>
                @php
                    $project->projectMembers = $project->projectMembers->map(fn($item) => $item->user)->unique();
                @endphp
                <p>Total {{ $project->projectMembers->count() }} users</p>
                <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                    {{-- List User's Avatar --}}
                    @foreach ($project->projectMembers as $key => $projectMember)
                        @if ($key > 5)
                            <li data-bs-toggle="tooltip"
                                data-popup="tooltip-custom"
                                data-bs-placement="top"
                                title="Khác"
                                class="avatar avatar-sm pull-up"
                                style="margin: 1px">
                                <div class="circle-around d-flex justify-content-center align-items-center"
                                    style="width: 2em; height: 2em;">
                                    <small>+{{ $project->projectMembers->count() - $key }}</small>
                                </div>
                            </li>
                        @break

                    @else
                        <li data-bs-toggle="tooltip"
                            data-popup="tooltip-custom"
                            data-bs-placement="top"
                            title="{{ $projectMember->full_name ?? '' }}"
                            class="avatar avatar-sm pull-up"
                            style="margin: 1px">
                            <img class="rounded-circle"
                                src="{{ $projectMember->avatar ?? asset('images/avatars/2.png') }}"
                                alt="Avatar" />
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</div>
</div>
