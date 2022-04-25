/*=========================================================================================
  File Name: auth-register.js
  Description: Auth register js file.
  ----------------------------------------------------------------------------------------
  Item Name: Exercise
  Author: Châu Ngọc Long
==========================================================================================*/
(function(window, document, $) {
  'use strict';

  let registerPage = (function() {
    // elements
    let $registerMultipleStepsWizard = $('.register-multi-steps-wizard');
    let $forms = $registerMultipleStepsWizard.find('form');
    let $btnNext = $('.btn-next');
    let $btnPrev = $('.btn-prev');
    let $btnSubmit = $('.btn-submit');
    let $birthDay = $('#birthday');
    let stepper = null;

    // configs
    const configs = {
      validate: {
        rules: {
          username: {
            server: '/register',
          },
          email: {
            server: '/register'
          },
          first_name: {
            server: '/register',
          },
          last_name: {
            server: '/register',
          },
          password: {
            server: '/register',
          },
          avatar: {
            server: '/register',
          },
          address: {
            server: '/register',
          },
          phone: {
            server: '/register',
          },
          birthday: {
            server: '/register',
          },
          password_confirmation: {
            required: true,
            equalTo: '#password'
          },
          gender: {
            server: '/register'
          }
        },
        messages: {
          password_confirmation: {
            required: 'Trường xác nhận mật khẩu không được để trống',
            equalTo: 'Trường xác nhận mật khẩu phải giống trường mật khẩu'
          }
        }
      }
    };

    // create form data to register
    const createFormDataRegister = () => {
      let totalFormSerializeArray = $forms.serializeArray();
      let fileAvatarInput = $forms.find('#avatar')[0];
      let fileAvatar = fileAvatarInput.files;
      let formData = new FormData();

      if (fileAvatar.length) {
        formData.append(fileAvatarInput.name, fileAvatar[0]);
      }

      totalFormSerializeArray.forEach((item) => {
        formData.append(item.name, item.value);
      });

      return formData;
    };

    // when register success
    const whenRegisterSuccess = (response) => {
      // disable submit button
      $btnSubmit.prop('disabled', true);

      Swal.fire({
        title: response.message,
        icon: 'success',
        toast: true,
        showConfirmButton: false,
        position: 'top-end',
        timer: 5000,
        timerProgressBar: true
      }).then(function() {
        window.location.href = response.data.links.redirectTo;
      });
    };

    // when register failed
    const whenRegisterFailed = (xhr) => {
      Swal.fire({
        title: xhr.responseJSON.message,
        icon: 'error',
        toast: true,
        showConfirmButton: false,
        position: 'top-end',
        timer: 3000,
        timerProgressBar: true
      }).then(function() {
        window.location.reload();
      });
    };

    const bindFunctions = () => {
      // button next click
      $btnNext.on('click', function(e) {
        e.preventDefault();

        let $form = $(this).parent().siblings('form');

        if ($form.valid()) {
          stepper.next();
        }
      });

      // button previous click
      $btnPrev.on('click', function(e) {
        stepper.previous();
      });

      // button submit click
      $btnSubmit.on('click', function(e) {
        e.preventDefault();

        let formData = createFormDataRegister();

        $.ajax({
          url: '/register',
          type: 'POST',
          data: formData,
          dataType: 'JSON',
          processData: false,
          cache: false,
          contentType: false,
        }).done(whenRegisterSuccess)
          .fail(whenRegisterFailed);
      });
    }

    const init = () => {
      // init stepper
      if ($forms.length) {
        stepper = new Stepper($registerMultipleStepsWizard[0]);
      }

      // init date picker
      if ($birthDay.length) {
        $birthDay.flatpickr({
          maxDate: 'today',
          dateFormat: 'd-m-Y',
          onReady: function (selectedDates, dateStr, instance) {
            if (instance.isMobile) {
              $(instance.mobileInput).attr('step', null);
            }
          }
        });
      }

      // init validate form
      $forms.each(function() {
        $(this).validate(configs.validate);
      });

      bindFunctions();
    }

    return {
      init: init
    }
  })();

  // when DOM load completed
  $(function() {
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    
    // start page
    registerPage.init();
  });
})(window, document, jQuery);