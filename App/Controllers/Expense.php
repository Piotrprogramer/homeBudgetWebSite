<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\ExpenseMenager;
use \App\Controllers\AuxiliaryMethods;
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
        if (ExpenseMenager::isEmptyUserArray()) {
            ExpenseMenager::copyDefaultCategory();
        }
        echo json_encode(ExpenseMenager::getExpenseList($_SESSION['user_id']));
    }

    /**
     * Show the new form page
     *
     * @return void
     */
    public function getUserPaymentsAction()
    {
        if (ExpenseMenager::isPaymentUserArray()) {
            ExpenseMenager::copyDefaultPaymentCategory();
        }
        echo json_encode(ExpenseMenager::paymentAsignetToUser());
    }

    /**
     * Add expense to server
     *
     * @return void
     */
    public function addExpenseAction()
    {
        $Expense = new ExpenseMenager($_POST);

        if ($Expense->save()) {

            Flash::addMessage('Wydatek dodany poprawnie', Flash::SUCCESS);

            View::renderTemplate('Expense/newExpense.html');

        } else {

            Flash::addMessage('Coś poszło nie tak', Flash::WARNING);

            View::renderTemplate('Expense/newExpense.html');
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
        $_POST["categoryName"] = AuxiliaryMethods::upperCaseFirstLetter($_POST["categoryName"]);
        if (isset($_SESSION['user_id'])) {
            ExpenseMenager::updateCategory($_POST);
            if($_POST['categoryLimit']) ExpenseMenager::setLimit($_POST);
            else ExpenseMenager::removeLimit($_POST);
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
            ExpenseMenager::deleteCategory($_POST);
            ExpenseMenager::deleteExpenses($_POST);
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
        $_POST["categoryName"] = AuxiliaryMethods::upperCaseFirstLetter($_POST["categoryName"]);
        if (isset($_SESSION['user_id'])) {

            if (ExpenseMenager::isAvailable($_SESSION['user_id'], $_POST["categoryName"])) {
       
                if (ExpenseMenager::addCategory($_POST)) {
                    if($_POST['categoryLimit']) ExpenseMenager::setLimit($_POST);
                    echo json_encode(true);
                } else
                    echo json_encode(false);
            } else
                echo json_encode(false);

        } else
            echo json_encode(false);
    }

    /**
     * if category available return true, otherwise false in JSON encode format
     *
     * @return string JSONencode
     */
    public function isCategoryAvailable()
    {
        $data = json_encode($_POST);
        $object = json_decode($data);

        $category = array(
            'categoryName' => $object->categoryName,
        );

        $categoryName = AuxiliaryMethods::upperCaseFirstLetter($category['categoryName']);
        
        if (ExpenseMenager::isAvailable($_SESSION['user_id'], $categoryName) ||  $object->categoryLimit != null)
            echo json_encode(true);
        else
            echo json_encode(false);
    }

    /**
     * if to id is assigned any categories return true, otherwise false
     *
     * @return string JSONencode
     */
    public function isExpenseAssigned(){
        $data = json_encode($_POST);
        $object = json_decode($data);
      
        if (ExpenseMenager::isAssigned($_SESSION['user_id'], $object->categoryDeleteId)) echo json_encode(true);
 
        else echo json_encode(false);
    }

    
    /**
     * return limit category
     *
     * @return string JSONencode
     */
    public function limitAction(){
        $userId = $_SESSION['user_id'];
        $category = $this->route_params['category'];
        
        //echo json_encode('jestes w srodku chuju' , JSON_UNESCAPED_UNICODE);

        echo json_encode(ExpenseMenager::getLimit( $userId, $category ) , JSON_UNESCAPED_UNICODE );
    }

    /**
     * return spended money of the mont
     *
     * @return string JSONencode
     */
    public function getSpendedMoneyAction(){
        $userId = $_SESSION['user_id'];
        $category = $this->route_params['category'];
        $date = $this->route_params['date'];

        $year = date('Y', strtotime($date));

        $month = date('m', strtotime($date));

        echo json_encode(ExpenseMenager::getSpendedMoney( $userId, $category,$month,$year ) , JSON_UNESCAPED_UNICODE );
    }
}