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


/**
 * Create list for income
 */
function createList(data, category) {

    for (let i = 0; i < data.length; i++) {

        if (i == 0) addButton(data[i].name, true, category);
        
        else addButton(data[i].name, false, category);
    }
}

/**
 * Add select option to expense form with name and value
 */
function addButton(name, isFirst, select_name) {
    const option = document.createElement("option");
    option.textContent = name;
    option.setAttribute("value", name);
    option.selected = isFirst;
    document.querySelector(select_name).appendChild(option);
}

//Set current date on defoult date value
document.getElementById('date').valueAsDate = new Date();

/**
 * Getting expense id, name from DB in Json value
 * 
 * make separete id and name and send it to adding button function
 */
$(document).ready(function () {
    $.ajax({
        url: '/expense/getUserCategory',
        method: 'POST',

        success: function (response) {
            createList($.parseJSON(response), "#Category");
        }, error: function () {
            alert('error: ');
        }
    });

});

/**
 * Getting expense id, name from DB in Json value
 * 
 * make separete id and name and send it to adding button function
 */
$(document).ready(function () {
    $.ajax({
        url: '/expense/getUserPayments',
        method: 'POST',
        success: function (response) {
            createList($.parseJSON(response), "#payment_method");
        }, error: function () {
            alert('error: ');
        }
    });
});


