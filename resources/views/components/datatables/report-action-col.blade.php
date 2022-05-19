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
        @php
            $userIsAdminGroup = auth()
                ->user()
                ->isAdminGroup();
            $pendingStatus = \App\Models\Report::STATUS_PENDING;
        @endphp

        @if ($report->status === $pendingStatus || $userIsAdminGroup)
            <a class="dropdown-item report-edit"
                data-url-edit="{{ route('reports.edit', $report) }}"
                data-url-update="{{ route('reports.update', $report) }}">
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

            <a class="dropdown-item report-delete"
                data-url-delete="{{ route('reports.destroy', $report) }}">
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
        @endif

        @if ($report->status === $pendingStatus && $userIsAdminGroup)
            <a class="dropdown-item report-approve"
                data-url-approve="{{ route('reports.approve', $report) }}">
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
                Approve
            </a>
        @endif
        @php
            unset($userIsAdminGroup, $pendingStatus);
        @endphp
    </div>
</div>
