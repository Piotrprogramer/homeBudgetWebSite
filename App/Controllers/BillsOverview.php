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
     * Get billans value 
     *
     * @return string JSON representation of the user's income statistic or null if array is empty
     */
    public static function getBilansAction(){
        $data = json_encode($_POST);
        $object = json_decode($data);
        
        $date = array(
            'date_start' => $object->beaginingDate,
            'date_end' => $object->endDate
        );

        $bills = BillsMenager::getBilans($date);

        echo json_encode($bills);
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
    public static function thisMonthExpenseAction(){
        $date = array(
            'date_start' => date('Y-m-01'),
            'date_end' => date('Y-m-d')
        );

        $expenses_list = BillsMenager::getUserExpenses($date);

        echo json_encode($expenses_list);
    }

    public static function thisMonthBilansAction(){
        $date = array(
            'date_start' => date('Y-m-01'),
            'date_end' => date('Y-m-d')
        );
        $bills = BillsMenager::getBilans($date);

        echo json_encode($bills);
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
    public static function lastMonthExpenseAction(){
        $date_start = date('Y-m-01 ', strtotime( '-1 month' ));
        $date_end = date("Y-m-t", strtotime($date_start));

        $date = array(
            'date_start' => $date_start,
            'date_end' => $date_end
        );

        $expenses_list = BillsMenager::getUserExpenses($date);

        echo json_encode($expenses_list);
    }

    /**
     * Geting total amount of income and expense
     *
     * @return string JSON amout array
     */
    public static function lastMonthBilansAction(){
        $date_start = date('Y-m-01 ', strtotime( '-1 month' ));
        $date_end = date("Y-m-t", strtotime($date_start));

        $date = array(
            'date_start' => $date_start,
            'date_end' => $date_end
        );

        $bills = BillsMenager::getBilans($date);

        echo json_encode($bills);
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
    public static function lastThereMonthExpenseAction(){
        $date_start = date('Y-m-01 ', strtotime( '-3 month' ));
        $date_end = date('Y-m-d');
        
        $date = array(
            'date_start' => $date_start,
            'date_end' => $date_end
        );

        $expenses_list = BillsMenager::getUserExpenses($date);

        echo json_encode($expenses_list);
    }

    /**
     * Geting total amount of income and expense
     *
     * @return string JSON amout array
     */
    public static function lastThereMonthBilansAction(){
        $date_start = date('Y-m-01 ', strtotime( '-3 month' ));
        $date_end = date('Y-m-d');
        
        $date = array(
            'date_start' => $date_start,
            'date_end' => $date_end
        );

        $bills = BillsMenager::getBilans($date);

        echo json_encode($bills);
    }

    /**
     * Geting total amount of income and expense
     *
     * @return string JSON amout array
     */
    public function customeIncomeRangeAction()
    {
        $data = json_encode($_POST);
        $object = json_decode($data);
        
        $date = array(
            'date_start' => $object->beaginingDate,
            'date_end' => $object->endDate
        );

        $income_list = BillsMenager::getUserIncomes($date);
       
        echo json_encode($income_list);
    }

    /**
     * Geting expenses statistic fom DB of last 3 month
     *
     * @return string JSON representation of the user's income statistic or null if array is empty
     */
    public static function customeExpenseRangeAction(){
        $data = json_encode($_POST);
        $object = json_decode($data);
        
        $date = array(
            'date_start' => $object->beaginingDate,
            'date_end' => $object->endDate
        );

        $expenses_list = BillsMenager::getUserExpenses($date);

        echo json_encode($expenses_list);
    }
}