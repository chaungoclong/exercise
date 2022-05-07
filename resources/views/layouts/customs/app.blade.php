@extends('layouts.contentLayoutMaster')

@section('vendor-style')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

  <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
  <!-- BEGIN: push vendor style -->
  @stack('vendor-style')
  <!-- END: push vendor style -->
@endsection

@section('page-style')
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-sweet-alerts.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
  <!-- BEGIN: push page style -->
  @stack('page-style')
  <!-- END: push page style -->
@endsection

@section('vendor-script')
  <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/validation/additional-methods.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/cleave/cleave.min.js')) }}"></script>
  <!-- BEGIN: push vendor script -->
  @stack('vendor-script')
  <!-- END: push vendor script -->
@endsection

@section('page-script')
  <script src="{{ asset(mix('js/scripts/common.js')) }}"></script>
  <script src="{{ asset(mix('js/scripts/helpers.js')) }}"></script>
  <script src="{{ asset(mix('js/scripts/customs/validate-server.js')) }}"></script>
  <script src="{{ asset(mix('js/scripts/extensions/ext-component-sweet-alerts.js')) }}"></script>
  <!-- BEGIN: push page script -->
  @stack('page-script')
  <!-- END: push page script -->
@endsection
