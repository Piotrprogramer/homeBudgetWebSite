$(document).ready(function () {

    var userId = '{{ user.id }}';

    /**
     * Validate the form
     */
    $('#formProfile').validate({
        rules: {
            name: 'required',
            email: {
                required: true,
                email: true,
                remote: {
                    url: '/account/validate-email',
                    data: {
                        ignore_id: function () {
                            return userId;
                        }
                    }
                }
            },
            password: {
                minlength: 6,
                validPassword: true
            }
        },
        messages: {
            email: {
                remote: 'email already taken'
            }
        }
    });

    /**
      * Show password toggle button
      */
    $('#inputPassword').hideShowPassword({
        show: false,
        innerToggle: 'focus'
    });
});

$(document).ready(function () {
    $("#edit-profile").click(function () {

        $("#formProfile").toggle(300);

        if (document.getElementById("formIncome").style.display === "") {
            $("#formIncome").toggle(300);
        }
        if (document.getElementById("formExpense").style.display === "") {
            $("#formExpense").toggle(300);
        }
        if (document.getElementById("formPayment").style.display === "") {
            $("#formPayment").toggle(300);
        }

    });

    $("#edit-income").click(function () {

        $("#formIncome").toggle(300);

        if (document.getElementById("formProfile").style.display === "") {
            $("#formProfile").toggle(300);
        }
        if (document.getElementById("formExpense").style.display === "") {
            $("#formExpense").toggle(300);
        }
        if (document.getElementById("formPayment").style.display === "") {
            $("#formPayment").toggle(300);
        }

    });

    $("#edit-expense").click(function () {

        $("#formExpense").toggle(300);

        if (document.getElementById("formProfile").style.display === "") {
            $("#formProfile").toggle(300);
        }
        if (document.getElementById("formIncome").style.display === "") {
            $("#formIncome").toggle(300);
        }
        if (document.getElementById("formPayment").style.display === "") {
            $("#formPayment").toggle(300);
        }

    });

    $("#edit-payment").click(function () {

        $("#formPayment").toggle(300);

        if (document.getElementById("formProfile").style.display === "") {
            $("#formProfile").toggle(300);
        }
        if (document.getElementById("formIncome").style.display === "") {
            $("#formIncome").toggle(300);
        }
        if (document.getElementById("formExpense").style.display === "") {
            $("#formExpense").toggle(300);
        }

    });
});
