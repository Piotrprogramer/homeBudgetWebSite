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
$(document).ready(async () => {

    await new Promise(resolve => setTimeout(resolve, 100));
    let limit = await getLimitCategory();

    let showLimit = document.querySelector('#set_limit');

    if (limit !== null) showLimit.innerHTML = limit;
    else showLimit.innerHTML = 'brak';
    displayLimitInfo();

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
    let option = document.createElement("option");
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


const getSpendMoney = async () => {
    try {
        let category_name = document.querySelector('#Category').value;
        let date = document.querySelector('#date').value;

        let res = await fetch(`../api/spendMoney/${category_name}/${date}`);
        let data = await res.json();

        return data;
    }
    catch (e) {
        console.log('error', e);
    }
}

const getLimitCategory = async () => {
    try {
        let category_name = document.querySelector('#Category').value;
        let total_limit = await fetch(`../api/limit/${category_name}`);
        let data = await total_limit.json();

        return data[0];
    }
    catch (e) {
        console.log('error', e);
    }
}

showLimit = async () => {
    let limit = await getLimitCategory();
    let showLimit = document.querySelector('#set_limit');
    showLimit.innerHTML = limit;

    if (limit) {
        showLimit.innerHTML = limit;
    } else showLimit.innerHTML = 'brak';
}

displayLimitInfo = async () => {
    let spend_money = await getSpendMoney();


    if (!spend_money) spend_money = '0';

    let spend_money_info = document.querySelector('#already_spent');
    let limit_set = await getLimitCategory();
    let limit_info = document.querySelector('#limit');
    let set_amount = document.querySelector('#amount').value;

    if (limit_set) {
        spend_money_info.innerHTML = 'W tym miesiącu wydałeś już: ' + spend_money;
        if (limit_set != 'brak') limit_info.innerHTML = (parseFloat(limit_set) - set_amount - parseFloat(spend_money)).toFixed(2);
        else limit_info.innerHTML = 'Brak limitu';

    } else {
        spend_money_info.innerHTML = 'Lista wydatków na wybrany produkt jest obecnie pusta';
        limit_info.innerHTML = limit_set;
    }
    setLimitCollors();
}

const category = document.querySelector('#Category');
category.addEventListener('change', async () => {
    showLimit();
    displayLimitInfo();
});

const amount = document.querySelector('#amount');
amount.addEventListener("input", async () => {
    showLimit();
    displayLimitInfo();
});

const date = document.querySelector('#date');
date.addEventListener('change', async () => {
    showLimit();
    displayLimitInfo();
});


setLimitCollors = async () => {
    let limit = document.querySelector('#set_limit').innerHTML;
    let limit_box = document.querySelector('#limit').innerHTML;

    let limit_div = document.getElementById("limit_inf");
    let spend = document.getElementById("already_spent");
    let left = document.getElementById("limit_box");

    if (limit == 'brak') {
        limit_div.style.color = 'black';
        left.style.color = 'black';
    } else {

        
        if (parseFloat(limit_box) >= 0) {
            limit_div.style.color = '#34b632';
            left.style.color = '#c2b449';

        } else {
            limit_div.style.color = '#da0202';
            left.style.color = '#da0202';
        }
    }
}

