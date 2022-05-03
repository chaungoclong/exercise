@extends('layouts.customs.app')

@section('title', 'Profile')

@push('vendor-style')
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endpush

@push('page-style')
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
@endpush

@section('content')
  <div class="row">
    <div class="col-12">
      <ul class="nav nav-pills mb-2" role="tablist">
        <!-- Account -->
        <li class="nav-item" role="presentation">
          <a class="nav-link active" data-bs-toggle="pill" data-bs-target="#account">
            <i data-feather="user" class="font-medium-3 me-50"></i>
            <span class="fw-bold">Account</span>
          </a>
        </li>
        <!-- security -->
        <li class="nav-item" role="presentation">
          <a class="nav-link" data-bs-toggle="pill" data-bs-target="#security">
            <i data-feather="lock" class="font-medium-3 me-50"></i>
            <span class="fw-bold">Security</span>
          </a>
        </li>
      </ul>

      {{-- Tab content --}}
      <div class="tab-content">
        <!-- account -->
        <div class="tab-pane show active" id="account" role="tabpanel">
          <div class="card">
            <div class="card-header border-bottom">
              <h4 class="card-title">Profile Details</h4>
            </div>
            <div class="card-body py-2 my-25">
              <!-- header section -->
              <div class="d-flex">
                <a href="#" class="me-25">
                  <img src="{{ $profile->avatar ?? '' }}" id="avatarPreview" class="uploadedAvatar rounded me-50"
                    alt="profile image" height="100" width="100" />
                </a>
                <!-- upload and reset button -->
                <div class="d-flex align-items-end mt-75 ms-1">
                  <div>
                    <label for="avatar" class="btn btn-sm btn-primary mb-75 me-75">Upload</label>
                    <input type="file" id="avatar" name="avatar" hidden accept="image/*" />
                    <p class="text-danger" id="avatar_error"></p>
                    <p class="mb-0">Allowed file types: png, jpg, jpeg.</p>
                  </div>
                </div>
                <!--/ upload and reset button -->
              </div>
              <!--/ header section -->

              <!-- form -->
              <form class="validate-form mt-2 pt-50" id="accountForm">
                <div class="row">
                  <div class="col-12" id="alertAccount"></div>
                </div>
                <div class="row">
                  <div class="col-12 col-sm-6 mb-1">
                    {{-- First name --}}
                    <label class="form-label" for="first_name">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="John"
                      value="{{ $profile->first_name }}" />
                    <span id="first_name_error"></span>
                  </div>

                  {{-- Last name --}}
                  <div class="col-12 col-sm-6 mb-1">
                    <label class="form-label" for="last_name">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Doe"
                      value="{{ $profile->last_name }}" />
                    <span id="last_name_error"></span>
                  </div>

                  {{-- Email --}}
                  <div class="col-12 col-sm-6 mb-1">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email"
                      value="{{ $profile->email }}" />
                    <span id="email_error"></span>
                  </div>

                  {{-- Username --}}
                  <div class="col-12 col-sm-6 mb-1">
                    <label class="form-label" for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username"
                      value="{{ $profile->username }}" />
                    <span id="username_error"></span>
                  </div>

                  {{-- Phone --}}
                  <div class="col-12 col-sm-6 mb-1 form-group">
                    <label class="form-label" for="phone">Phone Number</label>
                    <input type="text" class="form-control account-number-mask" id="phone" name="phone"
                      placeholder="Phone Number" value="{{ $profile->phone }}" />
                    <span id="phone_error"></span>
                  </div>


                  {{-- Address --}}
                  <div class="col-12 col-sm-6 mb-1">
                    <label class="form-label" for="address">Address</label>
                    <input type="text" class="form-control" id="address" name="address" placeholder="Your Address"
                      value="{{ $profile->address }}" />
                    <span id="address_error"></span>
                  </div>

                  {{-- Birthday --}}
                  <div class="col-12 col-sm-6 mb-1">
                    <label class="form-label" for="birthday">BirthDay</label>
                    <input type="text" class="form-control picker" name="birthday" id="birthday"
                      value="{{ $profile->birthday }}" />
                    <span id="birth_error"></span>
                  </div>

                  {{-- Gender --}}
                  <div class="col-12 col-sm-6 mb-1">
                    <label class="d-block form-label">Gender</label>
                    <div class="form-check my-50">
                      <x-input type="radio" id="gender_male" name="gender" class="form-check-input"
                        value="{{ \App\Models\User::GENDER_MALE }}" :checked="$profile->gender == '1'" />
                      <label class="form-check-label" for="gender_male">Nam</label>
                    </div>
                    <div class="form-check">
                      <x-input type="radio" id="gender_female" name="gender" class="form-check-input"
                        value="{{ \App\Models\User::GENDER_FEMALE }}" :checked="$profile->gender == '0'" />
                      <label class="form-check-label" for="gender_female">Nữ</label>
                    </div>
                    <span id="gender_error"></span>
                  </div>

                  {{-- Submit --}}
                  <div class="col-12">
                    <button type="submit" class="btn btn-primary mt-1 me-1" id="saveAccount">Save changes</button>
                  </div>
                </div>
              </form>
              <!--/ form -->
            </div>
          </div>
        </div>

        <!-- security -->
        <div class="tab-pane show" id="security" role="tabpanel">
          <div class="card">
            <div class="card-header border-bottom">
              <h4 class="card-title">Change Password</h4>
            </div>
            <div class="card-body pt-1">
              <!-- form -->
              <form class="validate-form" id="changePasswordForm">
                <div class="row">
                  {{-- Current password --}}
                  <div class="col-12 col-sm-6 mb-1">
                    <label class="form-label" for="current_password">Current password</label>
                    <div class="input-group form-password-toggle input-group-merge">
                      <input type="password" class="form-control" id="current_password" name="current_password"
                        placeholder="Enter current password" />
                      <div class="input-group-text cursor-pointer">
                        <i data-feather="eye"></i>
                      </div>
                    </div>
                    <span id="current_password_error"></span>
                  </div>
                </div>

                <div class="row">
                  {{-- New password --}}
                  <div class="col-12 col-sm-6 mb-1">
                    <label class="form-label" for="new_password">New Password</label>
                    <div class="input-group form-password-toggle input-group-merge">
                      <input type="password" id="new_password" name="new_password" class="form-control"
                        placeholder="Enter new password" />
                      <div class="input-group-text cursor-pointer">
                        <i data-feather="eye"></i>
                      </div>
                    </div>
                    <span id="new_password_error"></span>
                  </div>

                  <div class="col-12 col-sm-6 mb-1">
                    {{-- Confirm new password --}}
                    <label class="form-label" for="confirm_new_password">Retype New Password</label>
                    <div class="input-group form-password-toggle input-group-merge">
                      <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password"
                        placeholder="Confirm your new password" />
                      <div class="input-group-text cursor-pointer"><i data-feather="eye"></i></div>
                    </div>
                    <span id="confirm_new_password_error"></span>
                  </div>
                  <div class="col-12">
                    <p class="fw-bolder">Password requirements:</p>
                    <ul class="ps-1 ms-25">
                      <li class="mb-50">Minimum 8 characters long - the more, the better</li>
                      <li class="mb-50">At least one lowercase character</li>
                      <li>At least one number, symbol, or whitespace character</li>
                    </ul>
                  </div>
                  <div class="col-12">
                    <button type="submit" class="btn btn-primary me-1 mt-1" id="changePassword">Save changes</button>
                  </div>
                </div>
              </form>
              <!--/ form -->
            </div>
          </div>
        </div>
      </div>
      {{-- /Tab content --}}
    </div>

    {{-- Pass data to JS --}}
    @php
      $jsUrl = [
          'profileUpdate' => route('profile.update'),
          'updateAvatar' => route('profile.update_avatar'),
      ];
    @endphp
    <input type="hidden" name="js_url" value="{{ json_encode($jsUrl) }}">
    {{-- /Pass data to JS --}}
  </div>
@endsection

@push('vendor-script')
  <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
@endpush

@push('page-script')
  <script src="{{ asset(mix('js/scripts/customs/validate-server.js')) }}"></script>
  <script src="{{ asset(mix('js/scripts/pages/page-user-profile.js')) }}"></script>
  <script>

  </script>
@endpush
