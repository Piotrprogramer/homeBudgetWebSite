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
class ExpenseMenager extends \Core\Model
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
        $sql = 'SELECT id FROM expenses_category_assigned_to_users WHERE name = :category AND user_id = :user_id';

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
            $sql = 'INSERT INTO expenses (id, user_id, expense_category_assigned_to_user_id,  payment_method_assigned_to_user_id, amount, date_of_expense, expense_comment)
                     VALUES (null, :user_id, :expense_category_assigned_to_user_id, :payment_method_assigned_to_user_id, :amount, :date_of_expense, :expense_comment)';
                     	
            $db = static::getDB();

            $stmt = $db->prepare($sql);

            $stmt->bindValue(':user_id', $_SESSION["user_id"], PDO::PARAM_INT);
            $stmt->bindValue(':expense_category_assigned_to_user_id', $this->getCategoryId(), PDO::PARAM_STR);
            $stmt->bindValue(':payment_method_assigned_to_user_id', $this->coment, PDO::PARAM_INT);   //  MISSING
            $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
            $stmt->bindValue(':date_of_expense', $this->date, PDO::PARAM_STR);
            $stmt->bindValue(':expense_comment', $this->coment, PDO::PARAM_STR);

            return $stmt->execute();
        }
        return false;
    }

    /**
     * Getting data about user income category
     *
     * @return boolean  True if getted correctly, false otherwise
     */
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

    /**
     * Past defoult income category to user category
     *
     * @return void  
     */
    public static function pastDefaultCategory($categorys)
    {
        $sql =
            'INSERT INTO expenses_category_assigned_to_users 
             VALUES(null, :user_id,:Transport),
               (null, :user_id,:Ksiazki),
                (null, :user_id,:Zywnosc),
                (null, :user_id,:Mieszkanie),
                (null, :user_id,:Telekomunikacja),
                (null, :user_id,:Zdrowie),
                (null, :user_id,:Odziez),         
                (null, :user_id,:Higiena),
                (null, :user_id,:Dzieci),
                (null, :user_id,:Rekreacja),
                (null, :user_id,:Wycieczka),
                (null, :user_id,:Oszczednosci),
                (null, :user_id,:Na_emeryture),
                (null, :user_id,:Splata_dlugu),
                (null, :user_id,:Prezent),
                (null, :user_id,:Inny)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

       $stmt->bindValue(':Transport', $categorys[0]['name'], PDO::PARAM_STR);
        $stmt->bindValue(':Ksiazki', $categorys[1]['name'], PDO::PARAM_STR);
        $stmt->bindValue(':Zywnosc', $categorys[2]['name'], PDO::PARAM_STR);
        $stmt->bindValue(':Mieszkanie', $categorys[3]['name'], PDO::PARAM_STR);
        $stmt->bindValue(':Telekomunikacja', $categorys[4]['name'], PDO::PARAM_STR);
        $stmt->bindValue(':Zdrowie', $categorys[5]['name'], PDO::PARAM_STR);
        $stmt->bindValue(':Odziez', $categorys[6]['name'], PDO::PARAM_STR);
        $stmt->bindValue(':Higiena', $categorys[7]['name'], PDO::PARAM_STR);
        $stmt->bindValue(':Dzieci', $categorys[8]['name'], PDO::PARAM_STR);
        $stmt->bindValue(':Rekreacja', $categorys[9]['name'], PDO::PARAM_STR);
        $stmt->bindValue(':Wycieczka', $categorys[10]['name'], PDO::PARAM_STR);
        $stmt->bindValue(':Oszczednosci', $categorys[11]['name'], PDO::PARAM_STR);
        $stmt->bindValue(':Na_emeryture', $categorys[12]['name'], PDO::PARAM_STR);
        $stmt->bindValue(':Splata_dlugu', $categorys[13]['name'], PDO::PARAM_STR);
        $stmt->bindValue(':Prezent', $categorys[14]['name'], PDO::PARAM_STR);
        $stmt->bindValue(':Inny', $categorys[15]['name'], PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

       $stmt->execute();
    }

    /**
     * Getting defoult income category
     *
     * @return void  array with income category
     */
    public static function copyDefaultCategory()
    {
        $sql = 'SELECT name FROM expenses_category_default';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        if ($stmt->execute()) {

            $categorys = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ExpenseMenager::pastDefaultCategory($categorys);
        }
    }

    /**
     * Check category assigned to user is empty
     *
     * @return bool  if epmty, false otherwise
     */
    public static function isEmptyUserArray()
    {
        $sql = 'SELECT id, name FROM expenses_category_assigned_to_users WHERE user_id = :user_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

        if ($stmt->execute()) {
            $expense = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($expense)) {
                return true;
            } else
                return false;
        } else
            return false;
    }
}