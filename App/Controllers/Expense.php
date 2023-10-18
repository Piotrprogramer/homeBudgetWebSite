<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\ExpenseMenager;
use \App\Auth;
use \App\Flash;

/**
 * Login controller
 *
 * PHP version 7.0
 */
class Expense extends Authenticated
{
    /**
     * Show the new form page
     *
     * @return void
     */
    public function newExpenseAction()
    {
        View::renderTemplate('Expense/newExpense.html');
    }

    /**
     * Show the new form page
     *
     * @return void
     */
    public function getUserCategoryAction()
    {   
        if(ExpenseMenager::isEmptyUserArray()){
            ExpenseMenager::copyDefaultCategory();
        }
        echo ExpenseMenager::expenseAsignetToUser();
    }

    /**
     * Show the new form page
     *
     * @return void
     */
    public function getUserPaymentsAction()
    {   
        if(ExpenseMenager::isPaymentUserArray()){
            ExpenseMenager::copyDefaultPaymentCategory();
        }
        echo ExpenseMenager::paymentAsignetToUser();
    }

    /**
     * Add expense to server
     *
     * @return void
     */
    public function addExpenseAction()
    {
        $Expense = new ExpenseMenager($_POST);

        if($Expense->save()){
            
            Flash::addMessage('Wydatek dodany poprawnie', Flash::SUCCESS);

            View::renderTemplate('Home/index.html');

        }else{

            Flash::addMessage('Coś poszło nie tak', Flash::WARNING);

            View::renderTemplate('Expense/newForm.html');
        }
    }
}