@extends('layouts.customs.auth')

@section('title', 'Register Page')

@push('vendor-style')
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/wizard/bs-stepper.min.css')) }}">
@endpush

@push('page-style')
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-wizard.css')) }}">
@endpush

@section('left-text')
  <!-- Left Text-->
  <div class="col-lg-3 d-none d-lg-flex align-items-center p-0">
    <div class="w-100 d-lg-flex align-items-center justify-content-center">
      <img class="img-fluid w-100" src="{{ asset('images/illustration/create-account.svg') }}" alt="multi-steps" />
    </div>
  </div>
  <!-- /Left Text-->
@stop

@section('auth-content')
  <!-- Register-->
  <div class="col-lg-9 d-flex align-items-center auth-bg px-2 px-sm-3 px-lg-5 pt-3">
    <div class="width-700 mx-auto">
      <div class="bs-stepper register-multi-steps-wizard shadow-none">
        <div class="bs-stepper-header px-0" role="tablist">
          <div class="step" data-target="#account-details" role="tab" id="account-details-trigger">
            <button type="button" class="step-trigger">
              <span class="bs-stepper-box">
                <i data-feather="home" class="font-medium-3"></i>
              </span>
              <span class="bs-stepper-label">
                <span class="bs-stepper-title">Account</span>
              </span>
            </button>
          </div>
          <div class="line">
            <i data-feather="chevron-right" class="font-medium-2"></i>
          </div>
          <div class="step" data-target="#personal-info" role="tab" id="personal-info-trigger">
            <button type="button" class="step-trigger">
              <span class="bs-stepper-box">
                <i data-feather="user" class="font-medium-3"></i>
              </span>
              <span class="bs-stepper-label">
                <span class="bs-stepper-title">Personal</span>
              </span>
            </button>
          </div>
          <div class="line">
            <i data-feather="chevron-right" class="font-medium-2"></i>
          </div>
          <div class="step" data-target="#submit" role="tab" id="submit-trigger">
            <button type="button" class="step-trigger">
              <span class="bs-stepper-box">
                <i data-feather="credit-card" class="font-medium-3"></i>
              </span>
              <span class="bs-stepper-label">
                <span class="bs-stepper-title">Submit</span>
              </span>
            </button>
          </div>
        </div>
        <div class="bs-stepper-content px-0 mt-4">
          <div id="account-details" class="content" role="tabpanel" aria-labelledby="account-details-trigger">
            <div class="content-header mb-2">
              <h2 class="fw-bolder mb-75">Account Information</h2>
            </div>
            <form>
              <div class="row">
                {{-- User name --}}
                <div class="col-md-6 mb-1">
                  <label class="form-label" for="username">Username</label>
                  <input type="text" name="username" id="username" class="form-control" placeholder="johndoe" />
                </div>

                {{-- Email --}}
                <div class="col-md-6 mb-1">
                  <label class="form-label" for="email">Email</label>
                  <input type="email" name="email" id="email" class="form-control" placeholder="john.doe@email.com"
                    aria-label="john.doe" />
                </div>

                {{-- Password --}}
                <div class="col-md-6 mb-1">
                  <label class="form-label" for="password">Password</label>
                  <div class="input-group input-group-merge form-password-toggle">
                    <input type="password" name="password" id="password" class="form-control"
                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                    <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                  </div>
                </div>

                {{-- Confirm password --}}
                <div class="col-md-6 mb-1">
                  <label class="form-label" for="password_confirmation">Confirm Password</label>
                  <div class="input-group input-group-merge form-password-toggle">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                    <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                  </div>
                </div>
              </div>
            </form>

            <div class="d-flex justify-content-between mt-2">
              <button class="btn btn-outline-secondary btn-prev" disabled>
                <i data-feather="chevron-left" class="align-middle me-sm-25 me-0"></i>
                <span class="align-middle d-sm-inline-block d-none">Previous</span>
              </button>
              <button class="btn btn-primary btn-next">
                <span class="align-middle d-sm-inline-block d-none">Next</span>
                <i data-feather="chevron-right" class="align-middle ms-sm-25 ms-0"></i>
              </button>
            </div>
          </div>

          {{-- Personal Info --}}
          <div id="personal-info" class="content" role="tabpanel" aria-labelledby="personal-info-trigger">
            <div class="content-header mb-2">
              <h2 class="fw-bolder mb-75">Personal Information</h2>
            </div>
            <form>
              <div class="row">
                {{-- First name --}}
                <div class="mb-1 col-md-6">
                  <label class="form-label" for="first_name">First Name</label>
                  <input type="text" name="first_name" id="first_name" class="form-control" placeholder="John" />
                </div>

                {{-- Last name --}}
                <div class="mb-1 col-md-6">
                  <label class="form-label" for="last_name">Last Name</label>
                  <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Doe" />
                </div>

                {{-- Phone --}}
                <div class="col-md-6 mb-1">
                  <label class="form-label" for="phone">Phone number</label>
                  <input type="text" name="phone" id="phone" class="form-control mobile-number-mask"
                    placeholder="(472) 765-3654" />
                </div>

                {{-- Birthday --}}
                <div class="col-md-6 mb-1">
                  <label class="form-label" for="birthday">BirthDay</label>
                  <input type="text" class="form-control picker" name="birthday" id="birthday" />
                </div>

                {{-- Avatar --}}
                <div class="col-md-12 mb-1">
                  <label for="avatar" class="form-label">Profile pic</label>
                  <input class="form-control" type="file" id="avatar" name="avatar" />
                </div>

                {{-- Address --}}
                <div class="col-12 mb-1">
                  <label class="form-label" for="address">Address</label>
                  <input type="text" name="address" id="address" class="form-control" placeholder="Address" />
                </div>

                {{-- Gender --}}
                <div class="mb-1">
                  <label class="d-block form-label">Gender</label>
                  <div class="form-check my-50">
                    <input type="radio" id="gender_male" name="gender" class="form-check-input"
                      value="{{ \App\Models\User::GENDER_MALE }}" />
                    <label class="form-check-label" for="gender_male">Male</label>
                  </div>
                  <div class="form-check">
                    <input type="radio" id="gender_female" name="gender" class="form-check-input"
                      value="{{ \App\Models\User::GENDER_FEMALE }}" />
                    <label class="form-check-label" for="gender_female">Female</label>
                  </div>
                </div>
              </div>
            </form>

            <div class="d-flex justify-content-between mt-2">
              <button class="btn btn-primary btn-prev">
                <i data-feather="chevron-left" class="align-middle me-sm-25 me-0"></i>
                <span class="align-middle d-sm-inline-block d-none">Previous</span>
              </button>
              <button class="btn btn-primary btn-next">
                <span class="align-middle d-sm-inline-block d-none">Next</span>
                <i data-feather="chevron-right" class="align-middle ms-sm-25 ms-0"></i>
              </button>
            </div>
          </div>
          <div id="submit" class="content" role="tabpanel" aria-labelledby="submit-trigger">
            <div class="d-flex justify-content-center mt-1">
              <button class="btn btn-success btn-submit">
                <i data-feather="check" class="align-middle me-sm-25 me-0"></i>
                <span class="align-middle d-sm-inline-block d-none">Submit</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /Register-->
@stop

@push('vendor-script')
  <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/wizard/bs-stepper.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/cleave/cleave.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/cleave/addons/cleave-phone.us.js')) }}"></script>
@endpush

@push('page-script')
  <script src="{{ asset(mix('js/scripts/customs/validate-server.js')) }}"></script>
@endpush
