/**
 * Getting income id, name from DB in Json value
 * 
 * make separete id and name and send it to adding button function
 */
$(document).ready(function () {
    /**
     * Set current date on defoult date value
     */
    document.getElementById('endDate').valueAsDate = new Date();

    /**
     * Show/hide date div button
     */
    $(".show-calendar").click(function () {
        $("#user-date-range-calendar").toggle();
        $("#user-date-range-button").toggle();
    });

    /**
     * Show/hide calendar div
     */
    $(".btn-outline-primary").click(function () {
        var x = document.getElementById("user-date-range-button");
        if (x.style.display === "none") {
            $("#user-date-range-calendar").toggle();
            $("#user-date-range-button").toggle();
        }
    });

    /**
     * Creating list from date in indicate DIV
     */
    function createList(div_name_of_list, array_list) {
        $(div_name_of_list).html("");
        var data = $.parseJSON(array_list);
        // Iterate through the data array.
        i = 0;
        for (let i = 0; i < data.length; i++) {
            // Create div's with list
            const div = document.createElement("div");
            div.innerHTML = (i + 1) + '). ' + data[i].name + ' = ' + data[i].total_amount;
            document.querySelector(div_name_of_list).appendChild(div);
        }
    }

    /**
    * This month bils creating button
    */
    $("#this-month").button().click(function () {
        $.ajax({
            url: '/billsOverview/thisMonthIncome',
            method: 'POST',
            success: function (response) {
                createList("#income-list", response);
            }, error: function () {
                alert('error: ');
            }
        });

        $.ajax({
            url: '/billsOverview/thisMonthExpense',
            method: 'POST',
            success: function (response) {
                createList("#expense-list", response);
            }, error: function () {
                alert('error: ');
            }
        });

        $("#diagram").html("this month list diagram");

        $("#diagram-panel").html("Bieżący miesiąc:");
    });

    /**
     * Last month bils creating button
     */
    $("#last-month").button().click(function () {
        $.ajax({
            url: '/billsOverview/lastMonthIncome',
            method: 'POST',
            success: function (response) {
                createList("#income-list", response);
            }, error: function () {
                alert('error: ');
            }
        });

        $.ajax({
            url: '/billsOverview/lastMonthExpense',
            method: 'POST',
            success: function (response) {
                createList("#expense-list", response);
            }, error: function () {
                alert('error: ');
            }
        });

        $("#diagram").html("last month list diagram");

        $("#diagram-panel").html("Poprzedni miesiąc:");
    });

    /**
     * Last there month bils creating button
     */
    $("#last-three-month").button().click(function () {
        $.ajax({
            url: '/billsOverview/lastThereMonthIncome',
            method: 'POST',
            success: function (response) {
                createList("#income-list", response);
            }, error: function () {
                alert('error: ');
            }
        });

        $.ajax({
            url: '/billsOverview/lastThereMonthExpense',
            method: 'POST',
            success: function (response) {
                createList("#expense-list", response);
            }, error: function () {
                alert('error: ');
            }
        });

        $("#diagram").html("last month list diagram");

        $("#diagram-panel").html("Poprzedni miesiąc:");
    });

    /**
    * Custom bils creating button
    */
    $("#custom-date").button().click(function () {
        $("#income-list").html("custom date list");
        $("#expense-list").html("custom date list");
        $("#diagram").html("custom date diagram");
        $("#user-date-range-calendar").toggle();
        $("#user-date-range-button").toggle();

        $("#diagram-panel").html("Twój zakres:");
    });
});