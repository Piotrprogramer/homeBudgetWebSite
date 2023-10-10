<?php

namespace App\Models;

use PDO;
use \App\Token;
use \App\Mail;
use \Core\View;

/**
 * User model
 *
 * PHP version 7.0
 */
class BillsMenager extends \Core\Model
{
    /**
     * Error messages
     *
     * @var array
     */
    public $errors = [];

    /**
     * Income data
     *
     * @var array
     */
    public $data = [];

    /**
     * Class constructor
     *
     * @param array $data Initial income data
     *
     * @return void
     */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
        //var_dump($data);

        ;
    }

    /**
     * Return array of incomes is exist, null otherwise
     *
     * @var array
     */
    public static function geUserIncomes()
    {
        $sql = 'SELECT income_category_assigned_to_user_id, SUM(amount) AS total_amount
                FROM incomes
                JOIN income_category_assigned_to_user_id ON incomes.income_category_assigned_to_user_id = income_category_assigned_to_user_id.id
                WHERE user_id = :user_id
                GROUP BY income_category_assigned_to_user_id.name
                ORDER BY total_amount DESC';
/*
        $sql = 'SELECT income_category_assigned_to_user_id, SUM(amount) AS total_amount
            FROM incomes
            WHERE user_id = :user_id
            GROUP BY income_category_assigned_to_user_id
            ORDER BY total_amount DESC';*/

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

        if ($stmt->execute()) {
            $userIncomes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($userIncomes)) {

                return BillsMenager::getUserIncomeName($userIncomes);

            } else null;
                
        } else
            return null;
    }

    /**
     * Geting category name from DB and including it to returning array
     *
     * @var array
     */
    public static function getUserIncomeName($userIncomes){

        $sql = 'SELECT income_category_assigned_to_user_id, SUM(amount) AS total_amount
        FROM incomes
        WHERE user_id = :user_id
        GROUP BY income_category_assigned_to_user_id
        ORDER BY total_amount DESC';
       
/*
        $sql = 'SELECT income_category_assigned_to_users.name AS income_category_assigned_to_user_id, SUM(amount) AS total_amount
        FROM incomes
        INNER JOIN incomes_category_assigned_to_users ON incomes.income_category_assigned_to_user_id = incomes_category_assigned_to_users.name
        WHERE user_id = 7
        GROUP BY incomes_category_assigned_to_users.name
        ORDER BY total_amount DESC';
        */ 

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

    if ($stmt->execute()) {
        if (!empty($userIncomes)) {
            $userIncomes = IncomeMenager::getUserIncomeName($userIncomes);

            var_dump($userIncomes);
            exit;

            return json_encode($userIncomes);
        } else null;
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else
        return null;
        
    }


    public static function expenseAsignetToUser()
    {
        $sql = 'SELECT id, name FROM expenses_category_assigned_to_users WHERE user_id = :user_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

        if ($stmt->execute()) {

            $income = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($income)) {
                return json_encode($income);
            } else
                return false;
        } else
            return false;
    }


}