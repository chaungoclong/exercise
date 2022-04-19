@extends('layouts/fullLayoutMaster')

@section('title', 'Register Multi Steps')

@section('vendor-style')
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/wizard/bs-stepper.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
@endsection

@section('page-style')
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-wizard.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/pages/authentication.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-sweet-alerts.css')) }}">
@endsection

@section('content')
<div class="auth-wrapper auth-cover">
  <div class="auth-inner row m-0">
    <!-- Brand logo-->
    <a class="brand-logo" href="#">
      <svg
        viewBox="0 0 139 95"
        version="1.1"
        xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink"
        height="28"
      >
        <defs>
          <lineargradient id="linearGradient-1" x1="100%" y1="10.5120544%" x2="50%" y2="89.4879456%">
            <stop stop-color="#000000" offset="0%"></stop>
            <stop stop-color="#FFFFFF" offset="100%"></stop>
          </lineargradient>
          <lineargradient id="linearGradient-2" x1="64.0437835%" y1="46.3276743%" x2="37.373316%" y2="100%">
            <stop stop-color="#EEEEEE" stop-opacity="0" offset="0%"></stop>
            <stop stop-color="#FFFFFF" offset="100%"></stop>
          </lineargradient>
        </defs>
        <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
          <g id="Artboard" transform="translate(-400.000000, -178.000000)">
            <g id="Group" transform="translate(400.000000, 178.000000)">
              <path
                class="text-primary"
                id="Path"
                d="M-5.68434189e-14,2.84217094e-14 L39.1816085,2.84217094e-14 L69.3453773,32.2519224 L101.428699,2.84217094e-14 L138.784583,2.84217094e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L6.71554594,44.4188507 C2.46876683,39.9813776 0.345377275,35.1089553 0.345377275,29.8015838 C0.345377275,24.4942122 0.230251516,14.560351 -5.68434189e-14,2.84217094e-14 Z"
                style="fill: currentColor"
              ></path>
              <path
                id="Path1"
                d="M69.3453773,32.2519224 L101.428699,1.42108547e-14 L138.784583,1.42108547e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L32.8435758,70.5039241 L69.3453773,32.2519224 Z"
                fill="url(#linearGradient-1)"
                opacity="0.2"
              ></path>
              <polygon
                id="Path-2"
                fill="#000000"
                opacity="0.049999997"
                points="69.3922914 32.4202615 32.8435758 70.5039241 54.0490008 16.1851325"
              ></polygon>
              <polygon
                id="Path-21"
                fill="#000000"
                opacity="0.099999994"
                points="69.3922914 32.4202615 32.8435758 70.5039241 58.3683556 20.7402338"
              ></polygon>
              <polygon
                id="Path-3"
                fill="url(#linearGradient-2)"
                opacity="0.099999994"
                points="101.428699 0 83.0667527 94.1480575 130.378721 47.0740288"
              ></polygon>
            </g>
          </g>
        </g>
      </svg>
      <h2 class="brand-text text-primary ms-1">Vuexy</h2>
    </a>
    <!-- /Brand logo-->

    <!-- Left Text-->
    <div class="col-lg-3 d-none d-lg-flex align-items-center p-0">
      <div class="w-100 d-lg-flex align-items-center justify-content-center">
        <img
          class="img-fluid w-100"
          src="{{asset('images/illustration/create-account.svg')}}"
          alt="multi-steps"
        />
      </div>
    </div>
    <!-- /Left Text-->

    <!-- Register-->
    <div class="col-lg-9 d-flex align-items-center auth-bg px-2 px-sm-3 px-lg-5 pt-3">
      <div class="width-700 mx-auto">
        <div class="bs-stepper register-multi-steps-wizard shadow-none">
            <div class="alert alert-danger">
              <div class="alert-body" id="displayError">abc</div>
            </div>
          <div class="bs-stepper-header px-0" role="tablist">
            <div class="step" data-target="#account-details" role="tab" id="account-details-trigger">
              <button type="button" class="step-trigger">
                <span class="bs-stepper-box">
                  <i data-feather="home" class="font-medium-3"></i>
                </span>
                <span class="bs-stepper-label">
                  <span class="bs-stepper-title">Account</span>
                  <span class="bs-stepper-subtitle">Enter username</span>
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
                  <span class="bs-stepper-subtitle">Enter Information</span>
                </span>
              </button>
            </div>
          </div>

          <div class="bs-stepper-content px-0 mt-4">
            <div id="account-details" class="content" role="tabpanel" 
              aria-labelledby="account-details-trigger">
              <div class="content-header mb-2">
                <h2 class="fw-bolder mb-75">Account Information</h2>
                <span>Enter your username password details</span>
              </div>
              <form>
                <div class="row">
                  <div class="col-md-6 mb-1">
                    <label class="form-label" for="username">Username</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="johndoe" />
                  </div>
                  <div class="col-md-6 mb-1">
                    <label class="form-label" for="email">Email</label>
                    <input
                      type="email"
                      name="email"
                      id="email"
                      class="form-control"
                      placeholder="john.doe@email.com"
                      aria-label="john.doe"
                    />
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 mb-1">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-group input-group-merge form-password-toggle">
                      <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control"
                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                      />
                      <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                    </div>
                  </div>
                  <div class="col-md-6 mb-1">
                    <label class="form-label" for="confirm-password">Confirm Password</label>
                    <div class="input-group input-group-merge form-password-toggle">
                      <input
                        type="password"
                        name="confirm_password"
                        id="confirm-password"
                        class="form-control"
                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                      />
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
                <button class="btn btn-primary btn-next" id="submitAccountDetails">
                  <span class="align-middle d-sm-inline-block d-none">Next</span>
                  <i data-feather="chevron-right" class="align-middle ms-sm-25 ms-0"></i>
                </button>
              </div>
            </div>

            <div id="personal-info" class="content" role="tabpanel" 
              aria-labelledby="personal-info-trigger">
              <div class="content-header mb-2">
                <h2 class="fw-bolder mb-75">Personal Information</h2>
                <span>Enter your Information</span>
              </div>
              <form>
                <div class="row">
                  <div class="mb-1 col-md-6">
                    <label class="form-label" for="first-name">First Name</label>
                    <input type="text" name="first_name" id="first-name" class="form-control" placeholder="John" />
                  </div>
                  <div class="mb-1 col-md-6">
                    <label class="form-label" for="last-name">Last Name</label>
                    <input type="text" name="last_name" id="last-name" class="form-control" placeholder="Doe" />
                  </div>
                  <div class="col-md-6 mb-1">
                    <label class="form-label" for="phone">Mobile number</label>
                    <input
                      type="text"
                      name="phone"
                      id="phone"
                      class="form-control mobile-number-mask"
                      placeholder="(472) 765-3654"
                    />
                  </div>

                  <div class="col-md-6 mb-1">
                    <label class="form-label" for="birthday">Birthday</label>
                    <input type="text" class="form-control picker" name="birthday" id="birthday">
                  </div>

                  <div class="col-12 mb-1">
                    <label for="avatar" class="form-label">Profile pic</label>
                    <input class="form-control" type="file" id="avatar" name="avatar">
                  </div>

                  <div class="col-12 mb-1">
                    <label class="form-label" for="address">Address</label>
                    <input
                      type="text"
                      name="address"
                      id="address"
                      class="form-control"
                      placeholder="Address"
                    />
                  </div>

                  <div class="col-md-6 mb-1">
                    <label class="d-block form-label">Gender</label>
                    <div class="form-check my-50">
                      <input type="radio" id="gender_male" name="gender" 
                        class="form-check-input" value="1">
                      <label class="form-check-label" for="gender_male">Male</label>
                    </div>
                    <div class="form-check">
                      <input type="radio" id="gender_female" name="gender" 
                        class="form-check-input" value="0">
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
                <button class="btn btn-primary btn-submit" id="submitPersonalInfo">
                  <span class="align-middle d-sm-inline-block d-none">Submit</span>
                  <i data-feather="chevron-right" class="align-middle ms-sm-25 ms-0"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('vendor-script')
<script src="{{ asset(mix('vendors/js/forms/wizard/bs-stepper.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/forms/cleave/cleave.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/forms/cleave/addons/cleave-phone.us.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
@endsection

@section('page-script')
<script src="{{asset(mix('js/scripts/pages/auth-register.js'))}}"></script>
<script src="{{ asset(mix('js/scripts/extensions/ext-component-sweet-alerts.js')) }}"></script>
@endsection
