<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\IncomeMenager;
use \App\Auth;
use \App\Flash;

/**
 * Login controller
 *
 * PHP version 7.0
 */
class Income extends Authenticated
{
    /**
     * Show the new form page
     *
     * @return void
     */
    public function newFormAction()
    {
        View::renderTemplate('Income/newForm.html');
    }

    /**
     * Show the new form page
     *
     * @return void
     */
    public function getUserCategoryAction()
    {
        if (IncomeMenager::isEmptyUserArray()) {
            IncomeMenager::copyDefaultCategory();
        }
        echo IncomeMenager::incomeAsignetToUser();
    }

    /**
     * Add income to server
     *
     * @return void
     */
    public function addIncomeAction()
    {
        $Income = new IncomeMenager($_POST);

        if ($Income->save()) {

            Flash::addMessage('Przychód dodany poprawnie', Flash::SUCCESS);

            View::renderTemplate('Home/index.html');

        } else {

            Flash::addMessage('Coś poszło nie tak', Flash::WARNING);

            View::renderTemplate('Income/newForm.html');
        }
    }

    /**
     * Get the current income list, from the session
     *
     * @return string JSON_encode 
     */
    public static function getIncome()
    {
        if (isset($_SESSION['user_id'])) {
            echo json_encode(IncomeMenager::getIncomeList($_SESSION['user_id']));
        }
    }

    /**
     * Update income list
     *
     * @return string JSON_encode 
     */
    public function updateCategoryAction()
    {
        if (isset($_SESSION['user_id'])) {
            if (IncomeMenager::updateCategory($_POST))
                echo json_encode('all good');
        }
    }

    /**
     * Delete income category from server 
     *
     * @return string JSON_encode 
     */
    public function deleteCategoryAction()
    {
        if (isset($_SESSION['user_id'])) {
            if (IncomeMenager::deleteCategory($_POST))
                echo json_encode('all good');
        }
    }

    /**
     * Add income category from server 
     *
     * @return string JSON_encode 
     */
    public function addCategoryAction()
    {
        if (isset($_SESSION['user_id'])) {
            if (IncomeMenager::addCategory($_POST)) {
                echo json_encode('all good');
            }
        }
    }
}