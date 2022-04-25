'use strict';

function getValueObjectAt(key, object) {
    if (typeof key !== 'string') {
        return null;
    }
    
    let keys = key.trim().split('.').map(k => k.trim());
    for (let k of keys) {
        if (k in object && typeof object === 'object') {
            object = object[k];
        } else {
            return null;
        }
    }
    
    return object;
}

const Route = {
    init: (namespace) => {
        Route.namespace = namespace;
        let action = $('body').data('action');

        Route.exec('common');
        Route.exec(action);
    },
    exec: (action) => {
        let func = getValueObjectAt(action, Route.namespace);
        if (func !== null && typeof func === 'function') {
            func();
        }
    }
}

window.Page = window.Page || {};

// register page
;(function(register) {
	register.init = () => {
        // reload error
        $('input, select').next('span.error').remove().end().removeClass('error');

        $('form').validate({
            rules: {
                email: {
                    required: true
                }
            }
        });

        // init date picker
		$('#birthday').flatpickr({
          allowInput: true,
          onReady: function (selectedDates, dateStr, instance) {
            if (instance.isMobile) {
              $(instance.mobileInput).attr('step', null);
            }
          }
        });
	}
})(window.Page.register = window.Page.register || {});


(function($, window, document) {
	$(function() {
        Route.init(window.Page);
    });
})($, window, document);
