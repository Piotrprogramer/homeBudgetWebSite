/* hide dive with limit option
  */
function hideLimitDivs() {
    let limit_div = document.querySelector('#limit_edit_div');
    limit_div.style.display = 'none';

    limit_div = document.querySelector('#limit_add_div');
    limit_div.style.display = 'none';
}

/* show dive with limit option
*/
function showLimitDivs() {
    let limit_div = document.querySelector('#limit_edit_div');
    limit_div.style.display = 'block';

    limit_div = document.querySelector('#limit_add_div');
    limit_div.style.display = 'block';
}

const edit_income = document.querySelector('#edit-income');
edit_income.addEventListener("click", (e) => hideLimitDivs()
);

const edit_payment = document.querySelector('#edit-payment');
edit_payment.addEventListener("click", (e) => hideLimitDivs()
);

const edit_expense = document.querySelector('#edit-expense');
edit_expense.addEventListener("click", (e) => showLimitDivs()
);

/*switch on/off input edit limit
*/
const edit_switch = document.querySelector('#edit_limit');
edit_switch.addEventListener("click", (e) => {

    const limit_input = document.querySelector('#edit_limit_value');

    if (edit_switch.checked) limit_input.removeAttribute("disabled");
    else limit_input.setAttribute("disabled", true);

});

/*switch on/off input add limit 
*/
const add_switch = document.querySelector('#add_limit');
add_switch.addEventListener("click", (e) => {

    const limit_input = document.querySelector('#add_limit_value');

    if (add_switch.checked) limit_input.removeAttribute("disabled");
    else limit_input.setAttribute("disabled", true);
});

/* clear input limit in edit modal window
*/
const editModalWindow = document.getElementById('editModal');
editModalWindow.addEventListener('show.bs.modal', (e) => {

    const limitSwitch = document.querySelector('#edit_limit');
    const limitInput = document.querySelector('#edit_limit_value');

    limitSwitch.checked = false;
    limitInput.value = null;
    limitInput.setAttribute('disabled', true);

});

/* clear input limit in add modal window
*/
const addModalWindow = document.getElementById('addModal');
addModalWindow.addEventListener('show.bs.modal', (e) => {

    const limitSwitch = document.querySelector('#add_limit');
    const limitInput = document.querySelector('#add_limit_value');

    limitSwitch.checked = false;
    limitInput.value = null;
    limitInput.setAttribute('disabled', true);

});
const inputValue = document.querySelector('#add_limit_value');
const inputSwitch = document.querySelector('#add_limit');