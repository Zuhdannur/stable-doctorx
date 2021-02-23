var OpAuthReminder = function() {
    var initValidationReminder = function(){
        jQuery('.js-validation-reminder').validate({
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
                'email': {
                    required: true,
                    email: true
                }
            },
        });
    };

    return {
        init: function () {
            // Init 
            initValidationReminder();
        }
    };
}();

// Initialize
jQuery(function(){ OpAuthReminder.init(); });