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
            Category: {
                required: true
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

/**
 * Add input radio button to Income form with name and id value
 */
function addButton(id, name) {
    const div = document.createElement("div");
    div.className = "form-check";
    div.innerHTML = `
        <input class="form-check-input" type="radio" name="Category" id="${id}" value="${name}">
        <label class="form-check-label" for="${id}"> ${name} </label>`;
    document.querySelector("#category").appendChild(div);
}

/**
 * Getting income id, name from DB in Json value
 * 
 * make separete id and name and send it to adding button function
 */
$(document).ready(function () {
    $.ajax({
        url: '/income/getUserCategory',
        method: 'POST',
        success: function (response) {
            var id = '';
            var name = '';
            for (let i = 0; i < response.length; i++) {
                if (response[i] == String.fromCharCode(58)) {
                    i++;
                    if (response[i] == String.fromCharCode(34)) {
                        i++;
                        var variable = '';
                        do {
                            variable += response[i];
                            i++;
                        }
                        while (response[i] != String.fromCharCode(34))

                        if (id == '') {
                            id = variable;
                        } else {
                            name = variable;
                        }
                        if (id != '' && name != '') {
                            addButton(id, name);
                            var id = '';
                            var name = '';
                        }
                    }
                }
            }
        }, error: function (error) {
            alert('error: ' + error);
        }
    });
});