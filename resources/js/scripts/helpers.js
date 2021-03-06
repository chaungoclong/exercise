// /*=========================================================================================
//   File Name: helper.js
//   Description: jQuery validation server side
//   ----------------------------------------------------------------------------------------
//   Item Name: Exercise
//   Author: Châu Ngọc Long
// ==========================================================================================*/

// (function($) {
//   $.validator.addMethod('server', function(value, input, url) {
//     /**
//      * generate data from input to send it to server
//      * @return object|formData
//      */
//     const generateData = () => {
//       // if input type is not file
//       if (input.type !== 'file') {
//         let data = {};
//         data[input.name] = value;

//         return data;
//       }

//       // if input type is file
//       let formData = new FormData();

//       if (input.files.length > 0) {
//         if (input.name.endsWith('[]')) {
//           input.files.forEach((file) => {
//             formData.append(input.name, file);
//           });
//         } else {
//           formData.append(input.name, input.files[0]);
//         }
//       }

//       return formData;
//     }

//     /**
//      * check input is valid
//      * @return bool: is input valid
//      */
//     const validate = () => {
//       let isValid = true;

//       // option for ajax request
//       let options = {
//         url: url,
//         method: 'POST',
//         data: generateData(),
//         dataType: 'JSON',
//         async: false,
//         statusCode: {
//           422: (xhr) => {
//             if (xhr.status === 422) {
//               let errors = xhr.responseJSON.errors || {};

//               if (errors[input.name] !== undefined) {
//                 isValid = false;

//                 $.validator.messages.server = errors[input.name][0];
//               }
//             }
//           }
//         }
//       };

//       // if input type is 'file' disable (processData, cache, contentType)
//       if (input.type === 'file') {
//         options = $.extend(options, {
//           processData: false,
//           cache: false,
//           contentType: false
//         });
//       };

//       // send request validate
//       $.ajax(options);

//       return isValid;
//     }

//     return validate();
//   });
// })(jQuery);

/**
 * Set XCSRF-TOKEN for Ajax Request
 * @param {*} selector
 */
function setToken(selector = 'meta[name="csrf-token"]') {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $(selector).attr('content')
        }
    });
}

// CUSTOM PLUGIN
(function ($) {
    /**
     * Show preview Image for input file
     */
    $.fn.showPreviewImage = function ($selectorPreviewImage) {
        $(this).on('change', function (e) {
            let file = e.target.files[0];
            let fileReader = new FileReader();
            let $previewImage = $($selectorPreviewImage);

            fileReader.addEventListener('load', function (e) {
                $previewImage.attr('src', e.target.result);
            });

            fileReader.readAsDataURL(file);
        });
    }
})(jQuery);
