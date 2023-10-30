
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

function createList(div_name_of_list, data, edit, deleteM, addNew) {
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

    newButton.setAttribute("type", "button");
    newButton.setAttribute("style", "text-align: right");

    newButton.setAttribute("data-bs-toggle", "modal");
    newButton.setAttribute("data-bs-target", '#' + addNew);

    newButton.innerHTML = "<i class='fas fa-square-plus fa-fw me-2'></i>Dodaj";
    li.appendChild(newButton);

    editModal(edit);
    deleteModal(deleteM);
    addModal(addNew);
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

        $.ajax({
            url: '/Income/getIncome',
            method: 'POST',

            success: function (response) {
                createList("#formIncome", $.parseJSON(response), 'editModal', 'deleteModal', 'addModal');
            }, error: function () {
                alert('error: ');
            }
        });
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

        $.ajax({
            url: '/Income/getIncome',
            method: 'POST',

            success: function (response) {
                createList("#formIncome", $.parseJSON(response), 'editModal', 'deleteModal', 'addModal');
            }, error: function () {
                alert('error: ');
            }
        });
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

        $.ajax({
            url: '/Income/getIncome',
            method: 'POST',

            success: function (response) {
                createList("#formIncome", $.parseJSON(response), 'editModal', 'deleteModal', 'addModal');
            }, error: function () {
                alert('error: ');
            }
        });
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
