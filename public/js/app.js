/**
 * Add jQuery Validation plugin method for a valid password
 * 
 * Valid passwords contain at least one letter and one number.
 */
$.validator.addMethod('validPassword',
    function (value, element, param) {

        if (value != '') {
            if (value.match(/.*[a-z]+.*/i) == null) {
                return false;
            }
            if (value.match(/.*\d+.*/) == null) {
                return false;
            }
        }

        return true;
    },
    'Must contain at least one letter and one number'
);

/**
 * Add jQuery show/hide password function to toggle button
 * 
 */
$(document).ready(function() {
    $("#show-password").change(function(){
        $(this).prop("checked") ?  $("#inputPassword").prop("type", "text") : $("#inputPassword").prop("type", "password");    
    });
});


/**
 * Add jQuery Validation plugin method for a valid income form
 * 
 * Valid income contains min value, multiple step value, Polish comment
 */
$(document).ready(function () {
    $("#myform").validate({
        rules: {
            amount: {
                required: true,
                min: 0.01,
                step: 0.01
            },
            date: {
                required: true,
                date: true,
                min: "2000-01-01", // Minimum date
                max: "2200-01-01" // Maximum date
            }
        },
        messages: {
            // Polish comment to action
            amount: {
                multiple: "Wprowadź wielokrotność kwoty 0.01.",
                min: "Podaj kwotę wiekszą niż 0,01",
                step: "Podaj wielokrotność kwoty 0,01",
                required: "To pole jest wymagane."
            },
            date: {
                required: "To pole jest wymagane.",
                min: "Wprowadź datę po 2000-01-01",
                max: "Wprowadź datę przed 2200-01-01"
            }
        },

        submitHandler: function (form) {
            // do other things for a valid form
            form.submit();
        }
    });
});

//Set current date on defoult date value
document.getElementById('date').valueAsDate = new Date();