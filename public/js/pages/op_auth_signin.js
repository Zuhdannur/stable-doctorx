var OpAuthSignIn = function() {
    var initValidationSignIn = function(){
        jQuery('.js-validation-signin').validate({
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
                'username': {
                    required: true,
                    minlength: 3
                },
                'password': {
                    required: true,
                    minlength: 5
                }
            }
        });
    };

    return {
        init: function () {
            // Init
            initValidationSignIn();
        }
    };
}();

// Initialize
jQuery(function(){ OpAuthSignIn.init(); });