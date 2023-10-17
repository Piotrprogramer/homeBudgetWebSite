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
    function createList(div_name_of_list, data) {

        $(div_name_of_list).html("");

        i = 0;
        for (let i = 0; i < data.length; i++) {
            const div = document.createElement("div");
            div.innerHTML = (i + 1) + '). ' + data[i].name + ' = ' + data[i].total_amount;
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
    function createChartPie(diagramData) {
        $("#diagram").html("");

        var pie = new d3pie("diagram", {
            header: {
                title: {
                    text: "Twoje wydatki:",
                    fontSize: 30
                }
            },
            data: {
                content: diagramData.map(function (datum) {
                    return {
                        label: datum.name,
                        value: parseFloat(datum.total_amount)
                    };
                })
            }
        });
    }

    /**
     * Creating list from date in indicate DIV
     */
        function bilsInfo(div_name_of_list, amountArray, range_date) {
 
            var income = parseFloat(amountArray[0].total);
            var expense =  parseFloat(amountArray[1].total);

            var bils ;

            $(div_name_of_list).html("");

            const div = document.createElement("div");
            div.innerHTML =  range_date;
            //div.style.color = "red";
            div.setAttribute('class', 'd-flex justify-content-center bils-inf');
            document.querySelector(div_name_of_list).appendChild(div);

            if (income > expense) {
                bils = income - expense;
                const div = document.createElement("div");
                div.innerHTML =  "Udało Ci się nieco zaoszczędzić : +"+bils.toFixed(2);
                div.style.color = "green";
                div.setAttribute('class', 'd-flex justify-content-center bils-information');
                document.querySelector(div_name_of_list).appendChild(div);
            }
            else{
                bils = expense - income;
                const div = document.createElement("div");
                div.innerHTML =  "Niestety jesteś pod kreską : -"+bils.toFixed(2);
                div.style.color = "red";
                div.setAttribute('class', 'd-flex justify-content-center bils-information');
                document.querySelector(div_name_of_list).appendChild(div);
            }   
        }

    /**
    * This month bils creating button
    */
    $("#this-month").button().click(function () {
        var x = document.getElementById("bilans-contetn");
        if (x.style.display === "none") {
            $("#bilans-contetn").toggle();
        }

        $.ajax({
            url: '/billsOverview/thisMonthIncome',
            method: 'POST',
            success: function (response) {
                createList("#income-list", $.parseJSON(response));
            }, error: function () {
                alert('error: ');
            }
        });

        $.ajax({
            url: '/billsOverview/thisMonthExpense',
            method: 'POST',
            success: function (response) {
                createList("#expense-list", $.parseJSON(response));
                createChartPie($.parseJSON(response));
            }, error: function () {
                alert('error: ');
            }
        });

        $.ajax({
            url: '/billsOverview/thisMonthBilans',
            method: 'POST',

            success: function (response) {
                bilsInfo("#range-name",$.parseJSON(response), "W tym miesiacu");
            }, error: function () {
                alert('error: ');
            }
        });
    });

    /**
     * Last month bils creating button
     */
    $("#last-month").button().click(function () {
        var x = document.getElementById("bilans-contetn");
        if (x.style.display === "none") {
            $("#bilans-contetn").toggle();
        }

        $.ajax({
            url: '/billsOverview/lastMonthIncome',
            method: 'POST',
            success: function (response) {
                createList("#income-list", $.parseJSON(response));
            }, error: function () {
                alert('error: ');
            }
        });

        $.ajax({
            url: '/billsOverview/lastMonthExpense',
            method: 'POST',
            success: function (response) {
                createList("#expense-list", $.parseJSON(response));
                createChartPie($.parseJSON(response));
            }, error: function () {
                alert('error: ');
            }
        });
        
        $.ajax({
            url: '/billsOverview/lastMonthBilans',
            method: 'POST',

            success: function (response) {
                bilsInfo("#range-name",$.parseJSON(response), "W poprzednim miesiącu");
            }, error: function () {
                alert('error: ');
            }
        });
    });

    /**
     * Last there month bils creating button
     */
    $("#last-three-month").button().click(function () {
        var x = document.getElementById("bilans-contetn");
        if (x.style.display === "none") {
            $("#bilans-contetn").toggle();
        }

        $.ajax({
            url: '/billsOverview/lastThereMonthIncome',
            method: 'POST',
            success: function (response) {
                createList("#income-list", $.parseJSON(response));
            }, error: function () {
                alert('error: ');
            }
        });

        $.ajax({
            url: '/billsOverview/lastThereMonthExpense',
            method: 'POST',
            success: function (response) {
                createList("#expense-list", $.parseJSON(response));
                createChartPie($.parseJSON(response));
            }, error: function () {
                alert('error: ');
            }
        });

        $.ajax({
            url: '/billsOverview/lastThereMonthBilans',
            method: 'POST',
            success: function (response) {
                bilsInfo("#range-name",$.parseJSON(response), "Przez ostatnie 3 miesiące");
            }, error: function () {
                alert('error: ');
            }
        });
    });

    /**
    * Custom bils creating button
    */
    $("#custom-date").button().click(function () {
        var x = document.getElementById("bilans-contetn");
        if (x.style.display === "none") {
            $("#bilans-contetn").toggle();
        }

        $("#user-date-range-calendar").toggle();
        $("#user-date-range-button").toggle();

        // $('#form').submit(function () {
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
                createChartPie(response);
            }, error: function () {
                alert('error: ');
            }
        })

        $.ajax({
            url: '/billsOverview/getBilans',
            method: 'POST',
            data: formData,
            dataType: "json",
            encode: true,

            success: function (response) {
                bilsInfo("#range-name",response, "W podanym zakresie");
            }, error: function () {
                alert('error: ');
            }
        });
    });
});