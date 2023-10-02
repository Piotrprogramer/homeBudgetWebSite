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
        $this->data = $data;
    }

    public function validate()
    {

    }

    /**
     * Save the user model with the current property values
     *
     * @return boolean  True if the user was saved, false otherwise
     */
    public function save()
    {

        // $this->validate();

        var_dump($this->data);
        echo "<br>".$this->data["amount"];
       
        if (empty($this->errors)) {

             $sql = 'INSERT INTO incomes (id, user_id, income_category_assigned_to_user_id, amount, date_of_income, income_comment)
                     VALUES (null, :user_id, :category, :amount, :date_of_income, :income_comment)';

             $db = static::getDB();
             $stmt = $db->prepare($sql);

             $stmt->bindValue(':user_id',  $_SESSION["user_id"], PDO::PARAM_INT);
             $stmt->bindValue(':category', $_POST["Category"], PDO::PARAM_STR);
             $stmt->bindValue(':amount', $_POST["amount"], PDO::PARAM_STR);
             $stmt->bindValue(':date_of_income', $_POST["date"], PDO::PARAM_STR);
             $stmt->bindValue(':income_comment', $_POST["coment"], PDO::PARAM_STR);

             return $stmt->execute();
         }
        return false;
    }
}