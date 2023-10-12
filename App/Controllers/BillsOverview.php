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
    public function testAction()
    {
        
        $date_start = date('Y-m-01 ', strtotime( '-3 month' ));
        $date_end = date('Y-m-d');
        
        $date = array(
            'date_start' => $date_start,
            'date_end' => $date_end
        );
        
        var_dump($date);

        exit;
    }
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
     * Geting income statistic fom DB of current month
     *
     * @return string JSON representation of the user's income statistic or null if array is empty
     */
    public function thisMonthIncomeAction()
    {
        $date = array(
            'date_start' => date('Y-m-01'),
            'date_end' => date('Y-m-d')
        );

        $income_list = BillsMenager::getUserIncomes($date);

        echo json_encode($income_list);
    }

    /**
     * Geting expenses statistic fom DB of current month
     *
     * @return string JSON representation of the user's income statistic or null if array is empty
     */
    public static function thisMonthExpense(){
        $date = array(
            'date_start' => date('Y-m-01'),
            'date_end' => date('Y-m-d')
        );

        $expenses_list = BillsMenager::geUserExpenses($date);

        echo json_encode($expenses_list);
    }

    /**
     * Geting income statistic fom DB of last month
     *
     * @return string JSON representation of the user's income statistic or null if array is empty
     */
    public function lastMonthIncomeAction()
    {
        $date_start = date('Y-m-01 ', strtotime( '-1 month' ));
        $date_end = date("Y-m-t", strtotime($date_start));

        $date = array(
            'date_start' => $date_start,
            'date_end' => $date_end
        );

        $income_list = BillsMenager::getUserIncomes($date);

        echo json_encode($income_list);
    }

    /**
     * Geting expenses statistic fom DB of last month
     *
     * @return string JSON representation of the user's income statistic or null if array is empty
     */
    public static function lastMonthExpense(){
        $date_start = date('Y-m-01 ', strtotime( '-1 month' ));
        $date_end = date("Y-m-t", strtotime($date_start));

        $date = array(
            'date_start' => $date_start,
            'date_end' => $date_end
        );

        $expenses_list = BillsMenager::geUserExpenses($date);

        echo json_encode($expenses_list);
    }
    
    /**
     * Geting income statistic fom DB of last 3 month
     *
     * @return string JSON representation of the user's income statistic or null if array is empty
     */
    public function lastThereMonthIncomeAction()
    {
        $date_start = date('Y-m-01 ', strtotime( '-3 month' ));
        $date_end = date('Y-m-d');
        
        $date = array(
            'date_start' => $date_start,
            'date_end' => $date_end
        );

        $income_list = BillsMenager::getUserIncomes($date);

        echo json_encode($income_list);
    }

    /**
     * Geting expenses statistic fom DB of last 3 month
     *
     * @return string JSON representation of the user's income statistic or null if array is empty
     */
    public static function lastThereMonthExpense(){
        $date_start = date('Y-m-01 ', strtotime( '-3 month' ));
        $date_end = date('Y-m-d');
        
        $date = array(
            'date_start' => $date_start,
            'date_end' => $date_end
        );

        $expenses_list = BillsMenager::geUserExpenses($date);

        echo json_encode($expenses_list);
    }
}