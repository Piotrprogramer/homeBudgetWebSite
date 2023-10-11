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
            var isFirst = true;
            for (let i = 0; i < response.length; i++) {

            }
        }, error: function () {
            alert('error: ');
        }
    });


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
    * Get this month bils
    */
    $("#this-month").button().click(function () {
        $("#income-list").html("this month list");
        $("#expense-list").html("this month list");
        $("#diagram").html("this month list diagram");
    });

    /**
     * Get last month bils
     */
    $("#last-month").button().click(function () {
        $("#income-list").html("last month list");
        $("#expense-list").html("last month list");
        $("#diagram").html("last month list diagram");
    });

    /**
     * Get last there month bils
     */
    $("#last-three-month").button().click(function () {
        $("#income-list").html("last three month list");
        $("#expense-list").html("last three month list");
        $("#diagram").html("last three month list diagram");
    });

    /**
    * Get last there month bils
    */
    $("#custom-date").button().click(function () {
        $("#income-list").html("custom date list");
        $("#expense-list").html("custom date list");
        $("#diagram").html("custom date diagram");
        $("#user-date-range-calendar").toggle();
        $("#user-date-range-button").toggle();
    });
});