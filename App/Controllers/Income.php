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
     * Show the login page
     *
     * @return void
     */
    public function newFormAction()
    {
        View::renderTemplate('Income/newForm.html');
    }

    /**
     * Add income to server
     *
     * @return void
     */
    public function addIncomeAction()
    {
        $Income = new IncomeMenager($_POST);
        //var_dump($_SESSION["user_id"]);
        //var_dump($_POST);
        echo "</br>";
        echo "\n We are in public function addAction()";
        $Income->save();
        echo "</br>";
        echo "\n We are after IncomeMenager::save();";
       

        exit();
    }

}