var OpAuthResetPassword = function() {

    var initValidationResetPassword = function(){
        jQuery('.js-validation-reset').validate({
            errorClass: 'invalid-feedback animated fadeInDown',
            errorElement: 'div',
            locale: 'id',
            errorPlacement: function(error, e) {
                jQuery(e).parents('.form-group > div').append(error);
            },
            highlight: function(e) {
                jQuery(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
            },
            success: function(e) {
                jQuery(e).closest('.form-group').removeClass('is-invalid');
                jQuery(e).remove();
                $("button").attr("disabled", false);
            },
            rules: {
                'email': {
                    required: true,
                    minlength: 3
                },
                'password': {
                    required: true,
                    minlength: 5
                },
                'password_confirmation': {
                    required: true,
                    equalTo: '#password'
                }
            }
        });
    };

    return {
        init: function () {
            // Init
            initValidationResetPassword();
        }
    };
}();

// Initialize
jQuery(function(){ OpAuthResetPassword.init(); });