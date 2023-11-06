
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

/**
 * Validation profile form
 * 
 */
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

/**
 * Reload payment list
 * 
 */
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

/**
 * Reload Income list
 * 
 */
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

/**
 * Reload Expense list
 * 
 */
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

/**
 * Create list of Income, expense, payment
 * 
 */
$(document).ready(function () {
    reloadIncome();
    reloadExpense();
    reloadPayment();
});

  /*
  * Validate method
  *
  */
  $(document).ready(function () {
    $("#update_value").validate({
      rules: {
        category_name: {
          required: true,
        }
      },
      messages: {
        // Polish comment to action
        category_name: {
          required: "To pole jest wymagane."
        }
      },
      success: function () {
        $('#updateIncome').removeAttr('disabled');
        $('#updateExpense').removeAttr('disabled');
        $('#updatePayment').removeAttr('disabled');
      }
    });

    $("#add_value").validate({
      rules: {
        newName: {
          required: true,
        }
      },
      messages: {
        // Polish comment to action
        newName: {
          required: "To pole jest wymagane."
        }
      },
      success: function () {
        $('#addNew').removeAttr('disabled');
        $('#addExpenseButton').removeAttr('disabled');
        $('#addPayButton').removeAttr('disabled');
      }
    });

    $("#update_value").on('input', function () {
      if ($("#category_name").empty()) {
        $("#updateIncome").attr('disabled', true);
        $("#updateExpense").attr('disabled', true);
        $("#updatePayment").attr('disabled', true);
      }
    });
  });

/**
 * Return setting button 
 * 
 */
function newSettingButton(class_name, edit, name, id, style, innerHtml) {

    const editButton = document.createElement('button');
    editButton.classList.add('btn');
    editButton.classList.add(style);
    editButton.classList.add(class_name);

    editButton.setAttribute("type", "button");
    editButton.setAttribute("style", "text-align: right");

    editButton.setAttribute("data-bs-toggle", "modal");
    editButton.setAttribute("data-bs-target", '#' + edit);
    if (name != '') editButton.setAttribute("data-bs-categoryName", name);
    if (id != '') editButton.setAttribute("data-bs-categoryId", id);

    editButton.innerHTML = innerHtml;

    return editButton;
}

/**
 * Add buttons , class etc.
 * 
 */
function createList(div_name_of_list, data, edit, deleteM, addNew, button_edit, button_delete, button_add) {
    $(div_name_of_list).html("");

    for (let i = 0; i < data.length; i++) {

        const li = document.createElement("li");

        li.classList.add('list-group-item');

        li.innerHTML =
            "<span style='padding:50'>" + data[i].name + "</span>";
        document.querySelector(div_name_of_list).appendChild(li);


        li.appendChild(newSettingButton(button_delete, deleteM, data[i].name, data[i].id,
            'btn-outline-danger', "<i class='fas fa-trash-can fa-fw me-2'></i>Usuń"));

        li.appendChild(newSettingButton(button_edit, edit, data[i].name, data[i].id,
            'btn-outline-success', "<i class='fas fa-wrench fa-fw me-2'></i>Edytuj"));
    }

    const li = document.createElement("li");
    li.classList.add('list-group-item');

    li.innerHTML =
        "<span style='padding:50'></span>";
    document.querySelector(div_name_of_list).appendChild(li);

    li.appendChild(newSettingButton(button_add, addNew, '', '',
        'btn-outline-success', "<i class='fas fa-square-plus fa-fw me-2'></i>Dodaj"));

    editModal(edit);
    deleteModal(deleteM);
    addModal(addNew);

    editModalButtons(div_name_of_list, button_edit);
    deleteModalButtons(div_name_of_list, button_delete);
    addModalButtons(div_name_of_list, button_add);
}

/**
 * Show/hide used and unused edit modal buttons
 * 
 */
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

/**
 * Show/hide used and unused delete modal buttons
 * 
 */
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

/**
 * Show/hide used and unused add modal buttons
 * 
 */
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

            $("#addNew").attr('disabled', true);
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

            $("#addExpenseButton").attr('disabled', true);
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

            $("#addPayButton").attr('disabled', true);
        });
    } 
}

/**
 * Add modal hendlers to edit buttons
 * 
 */
function editModal(name) {
    var editModal = document.getElementById(name)
    editModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget

        var categoryName = button.getAttribute('data-bs-categoryName')
        var categoryId = button.getAttribute('data-bs-categoryId')

        var modalTitle = editModal.querySelector('.modal-title')
        var modalCategoryName = editModal.querySelector('.modal-body #category_name')
        var modalIdValue = editModal.querySelector('.modal-body #categoryId')

        modalTitle.textContent = 'Zamień "' + categoryName + '"'
        modalCategoryName.value = categoryName
        modalIdValue.value = categoryId
    })
}

/**
 * Add modal hendlers to delete buttons
 * 
 */
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

/**
 * Add modal hendlers to add buttons
 * 
 */
function addModal(name) {
    var editModal = document.getElementById(name)
    editModal.addEventListener('show.bs.modal', function (event) {
        var modalCategoryName = editModal.querySelector('.modal-body #newName')
        modalCategoryName.value = ''
    })
}

/**
 * Update income function
 * 
 */




$(document).ready(function () {
    $("#updateIncome").button().click(function () {
        if ($("#update_value").valid()) {
            var editForm = {
                category_name: $("#category_name").val(),
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
        }
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

/**
 * Update expense function
 * 
 */

$(document).ready(function () {
    $("#updateExpense").button().click(function () {

        var editForm = {
            category_name: $("#category_name").val(),
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

/**
 * Update payment function
 * 
 */
$(document).ready(function () {
    $("#updatePayment").button().click(function () {

        var editForm = {
            category_name: $("#category_name").val(),
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

/**
 * Show/hide main menu buttons
 * 
 */
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
