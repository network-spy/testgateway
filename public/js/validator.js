Validator = {
    init: function() {
        if($('#paymentForm').length) {
            $('#paymentForm').submit(function( event ) {
                return Validator.validateFormData();
            });
        }
    },

    validateFormData: function() {
        var reqTextFields = ['amount', 'customer_full_name', 'cc_holder_name', 'cc_number', 'cc_expiration', 'cc_ccv2'];
        var noErrors = true;
        Validator.clearErrors();
        for(var i=0; i < reqTextFields.length; i++){
            if($('#'+reqTextFields[i]).val().length < 1) {
                Validator.showError(reqTextFields[i], "Field is required")
                noErrors = false;
            }
        }
        if(isNaN(parseFloat($('#amount').val())) && $('#amount').val().length > 0) {
            Validator.showError('amount', 'Amount has to be numeric');
            noErrors = false;
        }
        if(/^\d{13,19}$/.test($('#cc_number').val())==false && $('#cc_number').val().length > 0) {
            Validator.showError('cc_number', 'Wrong credit card number');
            noErrors = false;
        }
        if(/^\d{2}\/\d{4}$/.test($('#cc_expiration').val())==false && $('#cc_expiration').val().length > 0) {
            Validator.showError('cc_expiration', 'Wrong credit card expiration date');
            noErrors = false;
        }
        if(/^\d{3,4}$/.test($('#cc_ccv2').val())==false && $('#cc_ccv2').val().length > 0) {
            Validator.showError('cc_ccv2', 'Wrong credit card CVV');
            noErrors = false;
        }
        return noErrors;
    },

    showError: function(fieldId, message)
    {
        if($('#'+fieldId).length > 0) {
            $('#'+fieldId).closest('.form-group').addClass('has-error');
            var span = $('<div />').addClass('error').html(message);
            span.insertAfter("#"+fieldId);
        }
    },

    clearErrors: function()
    {
        var elements = $('.error');
        while(elements.length) {
            $(elements[0]).closest('.form-group').removeClass('has-error');
            elements[0].remove();
            elements = $('.error');
        }
    }
}

$(function() {
    Validator.init();
});