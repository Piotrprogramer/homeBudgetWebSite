
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
    'Musi zawierać conajmniej jedną cyfre'
);

/**
 * Add jQuery show/hide password function to toggle button
 * 
 */
$(document).ready(function () {
    $("#show-password").change(function () {
        $(this).prop("checked") ? $("#inputPassword").prop("type", "text") : $("#inputPassword").prop("type", "password");
    });
});


$(document).ready(function () {
    /**
     * Validate the form
     */
    $('#formProfile').validate({
        rules: {
            name: 'required',
            email: {
                required: true,
                email: true,
            },
            password: {
                minlength: 6,
                validPassword: true
            },
            messages: {
                // Polish comment to action
                password: {
                    minlength: "Hasło powinno zawierać conajmniej 6 liter",
                    validPassword: "Hasło nie poprawne"
                }
            }
        },
    });

    /**
      * Show password toggle button
      */
    $('#inputPassword').hideShowPassword({
        show: false,
        innerToggle: 'focus'
    });
});

function reloadPayment() {
    setTimeout(function () {
        $.ajax({
            url: '/Payment/getPayment',
            method: 'POST',

            success: function (response) {
                createList("#formPayment", $.parseJSON(response), 'editModal', 'deleteModal', 'addModal',
                    'edit_py', 'delete_py', 'add_py');
            }, error: function () {
                alert('error: ');
            }
        });
    }, 300);
}

function reloadIncome() {
    setTimeout(function () {
        $.ajax({
            url: '/Income/getIncome',
            method: 'POST',

            success: function (response) {
                createList("#formIncome", $.parseJSON(response), 'editModal', 'deleteModal', 'addModal',
                    'edit_in', 'delete_in', 'add_in');
            }, error: function () {
                alert('error: ');
            }
        });
    }, 300);
}

function reloadExpense() {
    setTimeout(function () {
        $.ajax({
            url: '/Expense/getExpense',
            method: 'POST',

            success: function (response) {
                createList("#formExpense", $.parseJSON(response), 'editModal', 'deleteModal', 'addModal',
                    'edit_ex', 'delete_ex', 'add_ex');
            }, error: function () {
                alert('error: ');
            }
        });
    }, 300);
}

$(document).ready(function () {
    reloadIncome();
    reloadExpense();
    reloadPayment();
});

function createList(div_name_of_list, data, edit, deleteM, addNew, button_edit, button_delete, button_add) {
    $(div_name_of_list).html("");

    for (let i = 0; i < data.length; i++) {

        const li = document.createElement("li");
        li.classList.add('list-group-item');

        li.innerHTML =
            "<span style='padding:50'>" + data[i].name + "</span>";
        document.querySelector(div_name_of_list).appendChild(li);

        const newButton = document.createElement('button');
        newButton.classList.add('btn');
        newButton.classList.add('btn-outline-success');
        newButton.classList.add(button_edit);

        newButton.setAttribute("type", "button");
        newButton.setAttribute("style", "text-align: right");

        newButton.setAttribute("data-bs-toggle", "modal");
        newButton.setAttribute("data-bs-target", '#' + edit);
        newButton.setAttribute("data-bs-categoryName", data[i].name);
        newButton.setAttribute("data-bs-categoryId", data[i].id);

        newButton.innerHTML = "<i class='fas fa-wrench fa-fw me-2'></i>Edytuj";

        const newButton2 = document.createElement('button');
        newButton2.classList.add('btn');
        newButton2.classList.add('btn-outline-danger');
        newButton2.classList.add(button_delete);

        newButton2.setAttribute("type", "button");
        newButton2.setAttribute("style", "text-align: right");

        newButton2.setAttribute("data-bs-toggle", "modal");
        newButton2.setAttribute("data-bs-target", '#' + deleteM);
        newButton2.setAttribute("data-bs-categoryName", data[i].name);
        newButton2.setAttribute("data-bs-categoryId", data[i].id);

        newButton2.innerHTML = "<i class='fas fa-trash-can fa-fw me-2'></i>Usuń";

        li.appendChild(newButton2);
        li.appendChild(newButton);
    }

    const li = document.createElement("li");
    li.classList.add('list-group-item');

    li.innerHTML =
        "<span style='padding:50'></span>";
    document.querySelector(div_name_of_list).appendChild(li);

    const newButton = document.createElement('button');
    newButton.classList.add('btn');
    newButton.classList.add('btn-outline-success');
    newButton.classList.add(button_add);

    newButton.setAttribute("type", "button");
    newButton.setAttribute("style", "text-align: right");

    newButton.setAttribute("data-bs-toggle", "modal");
    newButton.setAttribute("data-bs-target", '#' + addNew);

    newButton.innerHTML = "<i class='fas fa-square-plus fa-fw me-2'></i>Dodaj";
    li.appendChild(newButton);

    editModal(edit);
    deleteModal(deleteM);
    addModal(addNew);
    editModalButtons(div_name_of_list, button_edit);
    deleteModalButtons(div_name_of_list, button_delete);
    addModalButtons(div_name_of_list, button_add);
}

function editModalButtons(divName, buttonId) {
    if (divName === "#formIncome") {
        $("." + buttonId).click(function () {
            if (document.getElementById("updateIncome").style.display == "none") {
                $("#updateIncome").toggle();
            }

            if (document.getElementById("updateExpense").style.display === "") {
                $("#updateExpense").toggle();
            }

            if (document.getElementById("updatePayment").style.display === "") {
                $("#updatePayment").toggle();
            }
        });
    }

    if (divName === "#formExpense") {

        $("." + buttonId).click(function () {

            if (document.getElementById("updateExpense").style.display == "none") {
                $("#updateExpense").toggle();
            }

            if (document.getElementById("updateIncome").style.display === "") {
                $("#updateIncome").toggle();
            }

            if (document.getElementById("updatePayment").style.display === "") {
                $("#updatePayment").toggle();
            }
        });
    }

    if (divName === "#formPayment") {
        $("." + buttonId).click(function () {

            if (document.getElementById("updatePayment").style.display == "none") {
                $("#updatePayment").toggle();
            }

            if (document.getElementById("updateIncome").style.display === "") {
                $("#updateIncome").toggle();
            }

            if (document.getElementById("updateExpense").style.display === "") {
                $("#updateExpense").toggle();
            }
        });
    }
}

function deleteModalButtons(divName, buttonId) {
    if (divName === "#formIncome") {
        $("." + buttonId).click(function () {
            if (document.getElementById("deleteIncome").style.display == "none") {
                $("#deleteIncome").toggle();
            }

            if (document.getElementById("deleteExpenseButton").style.display === "") {
                $("#deleteExpenseButton").toggle();
            }

            if (document.getElementById("deletePaymentButton").style.display === "") {
                $("#deletePaymentButton").toggle();
            }
        });
    }

    if (divName === "#formExpense") {

        $("." + buttonId).click(function () {

            if (document.getElementById("deleteExpenseButton").style.display == "none") {
                $("#deleteExpenseButton").toggle();
            }

            if (document.getElementById("deleteIncome").style.display === "") {
                $("#deleteIncome").toggle();
            }

            if (document.getElementById("deletePaymentButton").style.display === "") {
                $("#deletePaymentButton").toggle();
            }
        });
    }

    if (divName === "#formPayment") {
        $("." + buttonId).click(function () {

            if (document.getElementById("deletePaymentButton").style.display == "none") {
                $("#deletePaymentButton").toggle();
            }

            if (document.getElementById("deleteIncome").style.display === "") {
                $("#deleteIncome").toggle();
            }

            if (document.getElementById("deleteExpenseButton").style.display === "") {
                $("#deleteExpenseButton").toggle();
            }
        });
    }
}

function addModalButtons(divName, buttonId) {
    if (divName === "#formIncome") {
        $("." + buttonId).click(function () {
            if (document.getElementById("addNew").style.display == "none") {
                $("#addNew").toggle();
            }

            if (document.getElementById("addExpenseButton").style.display === "") {
                $("#addExpenseButton").toggle();
            }

            if (document.getElementById("addPayButton").style.display === "") {
                $("#addPayButton").toggle();
            }
        });
    }

    if (divName === "#formExpense") {

        $("." + buttonId).click(function () {

            if (document.getElementById("addExpenseButton").style.display == "none") {
                $("#addExpenseButton").toggle();
            }

            if (document.getElementById("addNew").style.display === "") {
                $("#addNew").toggle();
            }

            if (document.getElementById("addPayButton").style.display === "") {
                $("#addPayButton").toggle();
            }
        });
    }

    if (divName === "#formPayment") {
        $("." + buttonId).click(function () {

            if (document.getElementById("addPayButton").style.display == "none") {
                $("#addPayButton").toggle();
            }

            if (document.getElementById("addNew").style.display === "") {
                $("#addNew").toggle();
            }

            if (document.getElementById("addExpenseButton").style.display === "") {
                $("#addExpenseButton").toggle();
            }
        });
    }
}

function editModal(name) {
    var editModal = document.getElementById(name)
    editModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget

        var categoryName = button.getAttribute('data-bs-categoryName')
        var categoryId = button.getAttribute('data-bs-categoryId')

        var modalTitle = editModal.querySelector('.modal-title')
        var modalCategoryName = editModal.querySelector('.modal-body #category-name')
        var modalIdValue = editModal.querySelector('.modal-body #categoryId')

        modalTitle.textContent = 'Zamień "' + categoryName + '"'
        modalCategoryName.value = categoryName
        modalIdValue.value = categoryId
    })
}

function deleteModal(name) {
    var editModal = document.getElementById(name)
    editModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget

        var categoryName = button.getAttribute('data-bs-categoryName')
        var categoryId = button.getAttribute('data-bs-categoryId')
        var modalTitle = editModal.querySelector('.modal-title')
        var modalIdValue = editModal.querySelector('.modal-body #categoryDeleteId')

        modalTitle.textContent = 'Na pewno chcesz usunąć "' + categoryName + '"'
        modalIdValue.value = categoryId
    })
}

function addModal(name) {
    var editModal = document.getElementById(name)
    editModal.addEventListener('show.bs.modal', function (event) {
        var modalCategoryName = editModal.querySelector('.modal-body #newName')
        modalCategoryName.value = ''
    })
}


$(document).ready(function () {
    $("#updateIncome").button().click(function () {

        var editForm = {
            category_name: $("#category-name").val(),
            categoryId: $("#categoryId").val(),
        };

        $.ajax({
            url: '/Income/updateCategory',
            method: 'POST',
            data: editForm,
            dataType: "json",
            encode: true,
        });

        reloadIncome();
    });

    $("#deleteIncome").button().click(function () {

        var deleteForm = {
            categoryId: $("#categoryDeleteId").val(),
        };
        $.ajax({
            url: '/Income/deleteCategory',
            method: 'POST',
            data: deleteForm,
            dataType: "json",
            encode: true,
        });

        reloadIncome();
    });

    $("#addNew").button().click(function () {
        var addForm = {
            categoryName: $("#newName").val(),
        };

        $.ajax({
            url: '/Income/addCategory',
            method: 'POST',
            data: addForm,
            dataType: "json",
            encode: true,

        });

        reloadIncome();
    });
});

$(document).ready(function () {
    $("#updateExpense").button().click(function () {

        var editForm = {
            category_name: $("#category-name").val(),
            categoryId: $("#categoryId").val(),
        };

        $.ajax({
            url: '/Expense/updateCategory',
            method: 'POST',
            data: editForm,
            dataType: "json",
            encode: true,
        });

        reloadExpense();
    });

    $("#deleteExpenseButton").button().click(function () {

        var deleteForm = {
            categoryId: $("#categoryDeleteId").val(),
        };
        $.ajax({
            url: '/Expense/deleteCategory',
            method: 'POST',
            data: deleteForm,
            dataType: "json",
            encode: true,
        });

        reloadExpense();
    });

    $("#addExpenseButton").button().click(function () {
        var addForm = {
            categoryName: $("#newName").val(),
        };

        $.ajax({
            url: '/Expense/addCategory',
            method: 'POST',
            data: addForm,
            dataType: "json",
            encode: true,

        });

        reloadExpense();
    });
});

$(document).ready(function () {
    $("#updatePayment").button().click(function () {

        var editForm = {
            category_name: $("#category-name").val(),
            categoryId: $("#categoryId").val(),
        };

        $.ajax({
            url: '/Payment/updateCategory',
            method: 'POST',
            data: editForm,
            dataType: "json",
            encode: true,
        });

        reloadPayment();
    });

    $("#deletePaymentButton").button().click(function () {

        var deleteForm = {
            categoryId: $("#categoryDeleteId").val(),
        };
        $.ajax({
            url: '/Payment/deleteCategory',
            method: 'POST',
            data: deleteForm,
            dataType: "json",
            encode: true,
        });

        reloadPayment();
    });

    $("#addPayButton").button().click(function () {
        var addForm = {
            categoryName: $("#newName").val(),
        };

        $.ajax({
            url: '/Payment/addCategory',
            method: 'POST',
            data: addForm,
            dataType: "json",
            encode: true,

        });

        reloadPayment();
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
