/*=========================================================================================
  File Name: auth-register.js
  Description: Auth register js file.
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: PIXINVENT
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/



(function($, window, document) {
  $(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let RegisterPage = (function($, window, document) {
      // config
      const Config = {
        // validate
        validate: {
          formAccountDetails: {
            rules: {
              username: {
                required: true
              },
              password: {
                required: true,
                minlength: 8
              },
              'confirm_password': {
                required: true,
                minlength: 8,
                equalTo: '#password'
              },
              email: {
                required: true,
                email: true
              }
            }
          },
          formPersonalInfo: {
            rules: {
              'first_name': {
                required: true
              },
              'last_name': {
                required: true
              },
              phone: {
                required: true
              },
              birthday: {
                required: true
              },
              address: {
                required: true
              },
              gender: {
                required: true
              }
            }
          }
        },

        // url
        url: {
          registerAccountDetais: () => '/register/account-details',
          registerPersonalInfo: () => '/register/personal-info',
        }
      }

      // form multiple step wizard
      let registerMultiStepsWizard = document.querySelector('.register-multi-steps-wizard');
      let numberedStepper = null;

      // form step
      let $accountDetailsStep = $('#account-details');
      let $personalInfoStep = $('#personal-info');

      // form
      let $formAccountDetails = $accountDetailsStep.find('form').first();
      let $formPersonalInfo = $personalInfoStep.find('form').first();

      // btn submit step
      let $btnSubmitAccountDetails = $('#submitAccountDetails');
      let $btnSubmitPersonalInfo = $('#submitPersonalInfo');

      // birthday date picker
      let $birthdayDatePicker = $('#birthday');

      // init date picker
      const initDatePicker = () => {
        if ($birthdayDatePicker.length) {
          $birthdayDatePicker.flatpickr({
            allowInput: true,
            onReady: function (selectedDates, dateStr, instance) {
              if (instance.isMobile) {
                $(instance.mobileInput).attr('step', null);
              }
            }
          });
        }
      }

      // init form
      const initForms = () => {
         if (registerMultiStepsWizard !== undefined && registerMultiStepsWizard !== null) {
          // create stepper
          numberedStepper = new Stepper(registerMultiStepsWizard);

          // validate form
          $formAccountDetails.validate(Config.validate.formAccountDetails);
          $formPersonalInfo.validate(Config.validate.formPersonalInfo);
        }
      }

      // render server error
      const displayValidateServer = (xhr) => {
        let html = ``;
      } 
      

      // handle events
      const handleEvents = () => {
        // register step 1: account details
        $btnSubmitAccountDetails.on('click', (e) => {
          e.preventDefault();

          if ($formAccountDetails.valid()) {
            $.post(
              Config.url.registerAccountDetais(),
              $formAccountDetails.serializeArray(),
              'json'
            ).done((res) => {
              numberedStepper.next();
            }).fail((xhr) => {
              Swal.fire({html: renderValidateServerError(xhr), icon: 'error'});
            });
          }
        });

        // register step 2: personal info
        $btnSubmitPersonalInfo.on('click', (e) => {
          e.preventDefault();

          if ($formPersonalInfo.valid()) {
            let formData = new FormData();

            // combine data in two form
            let allPayload = [
              ...$formAccountDetails.serializeArray(),
              ...$formPersonalInfo.serializeArray()
            ];

            // add data to form data
            allPayload.forEach((item) => {
              formData.append(item.name, item.value);
            });

            // get file 
            let fileAvatar = $formPersonalInfo.find('#avatar')[0].files;
            if (fileAvatar.length) {
              formData.append('avatar', fileAvatar[0]);
            }

            $.ajax({
              url: Config.url.registerPersonalInfo(),
              method: 'POST',
              dataType: 'JSON',
              data: formData,
              cache:false,
              contentType: false,
              processData: false
            }).done((res) => {
              console.log(res);
            }).fail((xhr) => {
              alert(xhr.responseJSON.message);
            });
          }
        });
      }



      const init = () => {
        // init form
        initForms();

        // handle events
        handleEvents();
      }

      return {
        init: init
      }
    })($, window, document);

    RegisterPage.init();
  })
})($, window, document);
