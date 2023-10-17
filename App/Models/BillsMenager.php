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
        };
    }

    /**
     * Return array of incomes if exist, null otherwise
     *
     * @var array
     */
    public static function getUserIncomes($date)
    {
        $sql =
            'SELECT 
                incomes_category_assigned_to_users.name,
                SUM(incomes.amount) AS total_amount
            FROM 
                incomes
            LEFT JOIN 
                incomes_category_assigned_to_users ON 
                incomes.income_category_assigned_to_user_id = incomes_category_assigned_to_users.id
            WHERE
                incomes.user_id = :user_id
           
            AND incomes.date_of_income BETWEEN :date_start AND :date_end
    
            GROUP BY 
                incomes.income_category_assigned_to_user_id
            ORDER BY total_amount DESC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $_SESSION["user_id"], PDO::PARAM_INT);
        $stmt->bindValue(':date_start', $date['date_start'], PDO::PARAM_STR);
        $stmt->bindValue(':date_end', $date['date_end'], PDO::PARAM_STR);

        if ($stmt->execute()) {
            $userIncomes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $userIncomes;
        }
    }

    /**
     * Return array of expenses if exist, null otherwise
     *
     * @var array
     */
    public static function getUserExpenses($date)
    {
        $sql =
            'SELECT
                SUM(expenses.amount) AS total_amount,
                expenses_category_assigned_to_users.name
            FROM
                expenses
            LEFT JOIN 
                expenses_category_assigned_to_users 
            ON 
                expenses.expense_category_assigned_to_user_id = expenses_category_assigned_to_users.id
            WHERE 
                expenses.user_id = :user_id
            AND 
                expenses.date_of_expense BETWEEN :date_start AND :date_end
            GROUP BY 
                expenses.expense_category_assigned_to_user_id
            ORDER BY 
                total_amount DESC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $_SESSION["user_id"], PDO::PARAM_INT);
        $stmt->bindValue(':date_start', $date['date_start'], PDO::PARAM_STR);
        $stmt->bindValue(':date_end', $date['date_end'], PDO::PARAM_STR);

        if ($stmt->execute()) {
            $userExpenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $userExpenses;
        }
    }

    /**
     * Return array of with sum amount of income and expense
     * @var array
     */
    public static function getBilans($date)
    {
        $sql =
            'SELECT 
                SUM(incomes.amount) AS total
            FROM 
                incomes
            WHERE 
                incomes.user_id = :user_id
            AND
                incomes.date_of_income BETWEEN :date_start AND :date_end
                
            UNION

            SELECT 
                SUM(expenses.amount) AS total
            FROM
                expenses
            WHERE 
                expenses.user_id = :user_id
            AND
                expenses.date_of_expense BETWEEN :date_start AND :date_end';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $_SESSION["user_id"], PDO::PARAM_INT);
        $stmt->bindValue(':date_start', $date['date_start'], PDO::PARAM_STR);
        $stmt->bindValue(':date_end', $date['date_end'], PDO::PARAM_STR);

        if ($stmt->execute()) {
            $userExpenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $userExpenses;
        }
    }
}