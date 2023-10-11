<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\BillsMenager;
use \App\Auth;
use \App\Flash;

/**
 * Login controller
 *
 * PHP version 7.0
 */
class BillsOverview extends Authenticated
{
    /**
     * Show the new form page
     *
     * @return void
     */
    public function billsOverviewAction()
    {
        View::renderTemplate('Bills/billsOverview.html');
    }

    /**
     * Geting income statistic fom DB
     *
     * @return string JSON representation of the user's income statistic or null if array is empty
     */
    public static function incomeOverviewAction()
    {
        $userIncomes = BillMsenager::geUserIncomes();

        if (!empty($userIncomes)) {
            echo "2x chuj"; exit;
            return json_encode($userIncomes);

        } else null;
    }
}