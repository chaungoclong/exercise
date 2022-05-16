<div class="d-flex justify-content-left align-items-center w-100">
    <div class="avatar-wrapper">
        <div class="avatar  me-1">
            <img src="{{ $user->avatar ?? '' }}"
                alt="Avatar"
                height="32"
                width="32">
        </div>
    </div>
    <div class="d-flex flex-column">
        <a href="{{ route('users.show', $user) }}"
            class="user_name text-truncate text-body text-break text-wrap">
            <span class="fw-bolder">
                {{ $user->full_name }}
            </span>
        </a>
        <small class="emp_post text-muted text-break text-wrap">
            {{ $user->email }}
        </small>
    </div>
</div>
