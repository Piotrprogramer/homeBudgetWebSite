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
class IncomeMenager extends \Core\Model
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

    public function validate()
    {
        // Amount
        if ($this->amount == '') {
            $this->errors[] = 'Amount is required';
        }

        if (isset($this->amount)) {
            if (preg_match('/^\d+(\.\d+)?$/', $this->amount) == 0) {
                $this->errors[] = 'Amount sholud be a number';
            }
        }

        // Date
        if ($this->date == '') {
            $this->errors[] = 'Date is required';
        }

        // Category
        if ($this->Category == '') {
            $this->errors[] = 'Category is required';
        }
    }

    /**
     * 
     * @return int Return id category assigned to user, 0 otherwise
     */
    public function getCategoryId()
    {
        $sql = 'SELECT id FROM incomes_category_assigned_to_users WHERE name = :category AND user_id = :user_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $_SESSION["user_id"], PDO::PARAM_INT);
        $stmt->bindValue(':category', $this->Category, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $cat_id = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($cat_id != null)
                return $cat_id["id"];

        } else
            return false;
    }

    /**
     * Save the user model with the current property values
     *
     * @return boolean  True if the user was saved, false otherwise
     */
    public function save()
    {
        $this->validate();

        if (empty($this->errors)) {
            $sql = 'INSERT INTO incomes (id, user_id, income_category_assigned_to_user_id, amount, date_of_income, income_comment)
                     VALUES (null, :user_id, :category, :amount, :date_of_income, :income_comment)';

            $db = static::getDB();

            $stmt = $db->prepare($sql);

            $stmt->bindValue(':user_id', $_SESSION["user_id"], PDO::PARAM_INT);
            $stmt->bindValue(':category', $this->getCategoryId(), PDO::PARAM_STR);
            $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
            $stmt->bindValue(':date_of_income', $this->date, PDO::PARAM_STR);
            $stmt->bindValue(':income_comment', $this->coment, PDO::PARAM_STR);

            return $stmt->execute();
        }
        return false;
    }

    /**
     * Getting data about user income category
     *
     * @return boolean  True if getted correctly, false otherwise
     */
    public static function incomeAsignetToUser()
    {
        $sql = 'SELECT id, name FROM incomes_category_assigned_to_users WHERE user_id = :user_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

        if ($stmt->execute()) {


            $income = $stmt->fetchAll(PDO::FETCH_ASSOC);

            //return $income;

            return json_encode($income);
        } else
            return false;
    }
}