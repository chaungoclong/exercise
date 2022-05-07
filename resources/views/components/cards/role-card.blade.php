<div class="col-xl-4 col-lg-6 col-md-6 role-card" id="role_card_{{ $role->id }}">
  <div class="card">
    <div class="card-body">
      <div class="d-flex justify-content-between">
        <span>Total {{ $role->users_count }} users</span>
        <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
          {{-- List User's Avatar --}}
          @foreach ($role->users as $user)
            <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="Vinnie Mostowy"
              class="avatar avatar-sm pull-up">
              <img class="rounded-circle" src="{{ $user->avatar ?? asset('images/avatars/2.png') }}" alt="Avatar" />
            </li>
          @endforeach
        </ul>
      </div>
      <div class="d-flex justify-content-between align-items-end mt-1 pt-25">
        <div class="role-heading">
          <h4 class="fw-bolder">{{ $role->name }}</h4>
          <a href="javascript:;" class="role-edit" data-url-edit="{{ route('roles.edit', $role) }}"
            data-url-update="{{ route('roles.update', $role) }}">
            <small class="fw-bolder">Edit Role</small>
          </a>
        </div>
        @if ($role->is_user_define)
          <a data-url-delete="{{ route('roles.destroy', $role) }}" class="text-body role-delete">
            <small class="fw-bolder text-danger">Remove Role</small>
          </a>
        @endif
      </div>
    </div>
  </div>
</div>
