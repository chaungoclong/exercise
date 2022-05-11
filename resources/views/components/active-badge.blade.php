@if ($status == \App\Models\User::STATUS_ACTIVE)
    <span class="badge bg-light-success rounded-pill text-capitalize">
        {{ __(\App\Models\User::STATUS_ACTIVE_NAME) }}
    </span>
@else
    <span class="badge bg-light-danger rounded-pill text-capitalize">
        {{ __(\App\Models\User::STATUS_INACTIVE_NAME) }}
    </span>
@endif
