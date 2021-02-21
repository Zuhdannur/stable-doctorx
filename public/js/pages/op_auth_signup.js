var OpAuthSignUp = function() {
    var initValidationSignUp = function(){
        jQuery('.js-validation-signup').validate({
            errorClass: 'invalid-feedback animated fadeInDown',
            errorElement: 'div',
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
                'first_name': {
                    required: true,
                    minlength: 3
                },
                'last_name': {
                    required: true,
                    minlength: 3
                },
                'username': {
                    required: true,
                    minlength: 3
                },
                'email': {
                    required: true,
                    email: true
                },
                'password': {
                    required: true,
                    minlength: 5
                },
                'password_confirmation': {
                    required: true,
                    equalTo: '#password'
                }
            },
        });
    };

    return {
        init: function () {
            // Init
            initValidationSignUp();
        }
    };
}();

// Initialize
jQuery(function(){ OpAuthSignUp.init(); });