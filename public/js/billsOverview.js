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
});

/**
 * Set current date on defoult date value
 */
document.getElementById('endDate').valueAsDate = new Date();


/**
 * Show/hide date div button
 */
$(document).ready(function () {
    $(".show-calendar").click(function () {
        $("#user-date-range-calendar").toggle();
        $("#user-date-range-button").toggle();
    });
});

/**
 * Show/hide calendar div
 */
$(document).ready(function () {
    $(".btn-outline-primary").click(function () {
        var x = document.getElementById("user-date-range-button");
        if (x.style.display === "none") {
            $("#user-date-range-calendar").toggle();
            $("#user-date-range-button").toggle();
        }
    });
});

