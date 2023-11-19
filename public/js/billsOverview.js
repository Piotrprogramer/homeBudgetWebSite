

/**
* display content
*/
function show_content() {
    var x = document.getElementById("content");

    if (x.style.display === "none") {
        $("#income").toggle(300);
        $("#expense").toggle(300);
        $("#range-name").toggle(300);
    } else {
        $("#income").toggle(300);
        $("#expense").toggle();
        $("#range-name").toggle();

        $("#income").toggle(300);
        $("#expense").toggle(300);
        $("#range-name").toggle(300);
    }
}

/**
* Creating list from date in indicate DIV
*/
function createList(div_name_of_list, data) {

    $(div_name_of_list).html("");

    i = 0;
    for (let i = 0; i < data.length; i++) {
        const div = document.createElement("div");
        div.innerHTML = (i + 1) + '). ' + data[i].name + ' = ' + data[i].total_amount;
        div.setAttribute('class', 'part_list')
        document.querySelector(div_name_of_list).appendChild(div);
    }

    if ($('.income-list').is(':empty')) {
        const div = document.createElement("div");
        div.innerHTML = "Lista przychodów jest pusta :(";
        document.querySelector(div_name_of_list).appendChild(div);
    }

    if ($('.expense-list').is(':empty')) {
        const div = document.createElement("div");
        div.innerHTML = "Lista wydatków jest pusta :)";
        document.querySelector(div_name_of_list).appendChild(div);
    }
}

/**
* Creating chart pie from data
*/
function createChartPie(diagramData,diagram_name) {
    $("#"+diagram_name).html("");
    var size = Math.min(innerHeight, innerWidth);
    var pie = new d3pie(diagram_name, {
        
        data: {
            content: diagramData.map(function (datum) {
                return {
                    label: datum.name,
                    value: parseFloat(datum.total_amount)
                };
            })
        },
        size: {
          canvasHeight: 250,
          canvasWidth: 250,
        }
    });
}

/**
 * Creating list from date in indicate DIV
 */
function bilsInfo(div_name_of_list, amountArray, range_date_info) {

    var mydata = JSON.parse(amountArray);

    var income = parseFloat(mydata["income"]);
    if (mydata["income"] == 'null') income = 0;

    var expense = parseFloat(mydata["expense"]);
    if (mydata["expense"] == 'null') expense = 0;

    var bils;
    $(div_name_of_list).html("");

    const div = document.createElement("div");
    div.innerHTML = range_date_info;
    div.setAttribute('class', 'd-flex justify-content-center bils-inf');
    document.querySelector(div_name_of_list).appendChild(div);

    if (Number.isNaN(income) && Number.isNaN(expense)) {
        const div = document.createElement("div");
        div.innerHTML = "Brak danych";
        div.setAttribute('class', 'd-flex justify-content-center bils-information');
        document.querySelector(div_name_of_list).appendChild(div);
    }
    else {
        if (income > expense) {
            bils = income - expense;
            const div = document.createElement("div");
            div.innerHTML = "Udało Ci się nieco zaoszczędzić : +" + bils.toFixed(2);
            div.style.color = "green";
            div.setAttribute('class', 'd-flex justify-content-center bils-information');
            document.querySelector(div_name_of_list).appendChild(div);
        }
        else if (income < expense) {
            bils = expense - income;
            const div = document.createElement("div");
            div.innerHTML = "Niestety jesteś pod kreską : -" + bils.toFixed(2);
            div.style.color = "red";
            div.setAttribute('class', 'd-flex justify-content-center bils-information');
            document.querySelector(div_name_of_list).appendChild(div);
        }
    }
}

/**
 * Getting income id, name from DB in Json value
 * 
 * make separete id and name and send it to adding button function
 */
$(document).ready(function () {
    /**
     * Set current date on defoult date value
     */
    document.getElementById('beaginingDate').valueAsDate = new Date();
    document.getElementById('endDate').valueAsDate = new Date();

    /**
     * Show/hide date div button
     */
    $(".show-calendar").click(function () {
        $("#user-date-range-calendar").toggle(300);
        $("#user-date-range-button").toggle(300);
    });

    /**
     * Show/hide calendar div
     */
    $(".btn-outline-primary").click(function () {
        var x = document.getElementById("user-date-range-button");
        if (x.style.display === "none") {
            $("#user-date-range-calendar").toggle(300);
            $("#user-date-range-button").toggle(300);
        }
    });


    /**
    * This month bils creating button
    */
    $("#this-month").button().click(function () {
        $.ajax({
            url: '/billsOverview/thisMonthIncome',
            method: 'POST',
            success: function (response) {
                createList("#income-list", $.parseJSON(response));
                createChartPie($.parseJSON(response), 'diagram_income');
            }, error: function () {
                alert('error: ');
            }
        });

        $.ajax({
            url: '/billsOverview/thisMonthExpense',
            method: 'POST',
            success: function (response) {
                createList("#expense-list", $.parseJSON(response));
                createChartPie($.parseJSON(response), 'diagram_expense');
            }, error: function () {
                alert('error: ');
            }
        });

        $.ajax({
            url: '/billsOverview/thisMonthBilans',
            method: 'POST',

            success: function (response) {
                bilsInfo("#range-name", response, "W tym miesiacu");
            }, error: function () {
                alert('error: ');
            }
        });

        show_content();
    });

    /**
     * Last month bils creating button
     */
    $("#last-month").button().click(function () {

        $.ajax({
            url: '/billsOverview/lastMonthIncome',
            method: 'POST',
            success: function (response) {
                createList("#income-list", $.parseJSON(response));
                createChartPie($.parseJSON(response), 'diagram_income');
            }, error: function () {
                alert('error: ');
            }
        });

        $.ajax({
            url: '/billsOverview/lastMonthExpense',
            method: 'POST',
            success: function (response) {
                createList("#expense-list", $.parseJSON(response));
                createChartPie($.parseJSON(response) , 'diagram_expense');
            }, error: function () {
                alert('error: ');
            }
        });

        $.ajax({
            url: '/billsOverview/lastMonthBilans',
            method: 'POST',

            success: function (response) {
                bilsInfo("#range-name", response, "W poprzednim miesiącu");
            }, error: function () {
                alert('error: ');
            }
        });

        show_content();
    });

    /**
     * Last there month bils creating button
     */
    $("#last-three-month").button().click(function () {

        $.ajax({
            url: '/billsOverview/lastThereMonthIncome',
            method: 'POST',
            success: function (response) {
                createList("#income-list", $.parseJSON(response));
                createChartPie($.parseJSON(response), 'diagram_income');
            }, error: function () {
                alert('error: ');
            }
        });

        $.ajax({
            url: '/billsOverview/lastThereMonthExpense',
            method: 'POST',
            success: function (response) {
                createList("#expense-list", $.parseJSON(response));
                createChartPie($.parseJSON(response), 'diagram_expense');
            }, error: function () {
                alert('error: ');
            }
        });

        $.ajax({
            url: '/billsOverview/lastThereMonthBilans',
            method: 'POST',
            success: function (response) {
                //alert(response);
                bilsInfo("#range-name", response, "Przez ostatnie 3 miesiące");
            }, error: function () {
                alert('error: ');
            }
        });

        show_content();
    });

    /**
    * Custom bils creating button
    */
    $("#custom-date").button().click(function () {

        $("#user-date-range-calendar").toggle(300);
        $("#user-date-range-button").toggle(300);

        var formData = {
            beaginingDate: $("#beaginingDate").val(),
            endDate: $("#endDate").val(),
        };

        $.ajax({
            url: '/billsOverview/customeIncomeRange',
            method: "POST",
            data: formData,
            dataType: "json",
            encode: true,

            success: function (response) {
                createList("#income-list", response);
                createChartPie(response, 'diagram_income');
            }, error: function () {
                alert('error: ');
            }
        })

        $.ajax({
            url: '/billsOverview/customeExpenseRange',
            method: "POST",
            data: formData,
            dataType: "json",
            encode: true,

            success: function (response) {
                createList("#expense-list", response);
                createChartPie(response, 'diagram_expense');
            }, error: function () {
                alert('error: ');
            }
        })

        $.ajax({
            url: '/billsOverview/getBilans',
            method: 'POST',
            data: formData,

            success: function (response) {
                bilsInfo("#range-name", response, "W podanym zakresie");
            }, error: function () {
                alert('error: ');
            }
        });
        show_content();
    });
});