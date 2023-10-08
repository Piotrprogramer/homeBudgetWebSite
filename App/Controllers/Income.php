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
        if(IncomeMenager::isEmptyUserArray()){
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

        if($Income->save()){
            
            Flash::addMessage('Przychód dodany poprawnie', Flash::SUCCESS);

            View::renderTemplate('Home/index.html');

        }else{

            Flash::addMessage('Coś poszło nie tak', Flash::WARNING);

            View::renderTemplate('Income/newForm.html');
        }
    }
}