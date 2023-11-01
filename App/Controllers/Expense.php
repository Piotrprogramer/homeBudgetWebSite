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

    /**
     * Get expense from server
     *
     * @return string JSON_encode format
     */
    public static function getExpense()
    {
        if (isset($_SESSION['user_id'])) {
            echo json_encode(ExpenseMenager::getExpenseList($_SESSION['user_id']));
        }
    }

    /**
     * Update expense from server
     *
     * @return string JSON_encode format
     */
    public function updateCategoryAction()
    {
        if (isset($_SESSION['user_id'])) {
            if (ExpenseMenager::updateCategory($_POST))
                echo json_encode('all good');
        }
    }

    /**
     * Delete expense from server
     *
     * @return string JSON_encode format
     */
    public function deleteCategoryAction()
    {
        if (isset($_SESSION['user_id'])) {
            if (ExpenseMenager::deleteCategory($_POST))
                echo json_encode('all good');
        }
    }

    /**
     * Add expense to server
     *
     * @return string JSON_encode format
     */
    public function addCategoryAction()
    {
        if (isset($_SESSION['user_id'])) {
            if (ExpenseMenager::addCategory($_POST)) {
                echo json_encode('all good');
            }
        }
    }
}