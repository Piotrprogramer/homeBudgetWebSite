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
            
            if(!empty($income)){
                return json_encode($income);
            }
            else return false;
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
            'INSERT INTO incomes_category_assigned_to_users 
             VALUES(null, :user_id,:Wynagrodzenie),
                (null, :user_id,:Odsetki),
                (null, :user_id,:Allegro),
                (null, :user_id,:Inne)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':Wynagrodzenie', $categorys[0]['name'], PDO::PARAM_STR);
        $stmt->bindValue(':Odsetki', $categorys[1]['name'], PDO::PARAM_STR);
        $stmt->bindValue(':Allegro', $categorys[2]['name'], PDO::PARAM_STR);
        $stmt->bindValue(':Inne', $categorys[3]['name'], PDO::PARAM_STR);
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
        $sql = 'SELECT name FROM incomes_category_default';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        if ($stmt->execute()) {

            $categorys = $stmt->fetchAll(PDO::FETCH_ASSOC);
            IncomeMenager::pastDefaultCategory($categorys);
        }   
    }

    /**
     * Check category assigned to user is empty
     *
     * @return bool  if epmty, false otherwise
     */
    public static function isEmptyUserArray()
    {
        $sql = 'SELECT id, name FROM incomes_category_assigned_to_users WHERE user_id = :user_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

        if ($stmt->execute()) {

            $income = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if(empty($income)){
                return true;
            }
            else return false;
        } else
            return false; 
    }

    /**
     * Check income assigned to user 
     * 
     * @param string $id The user ID
     *
     * @return mixed Income object if found, false otherwise
     */
    public static function getIncomeList($id)
    {
        $sql = 
        'SELECT name,id FROM 
            incomes_category_assigned_to_users 
        WHERE 
            incomes_category_assigned_to_users.user_id = :id
        ';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Update income category 
     * 
     * @param bool 
     */
    public static function updateCategory($data)
    {  
        $sql = 
            'UPDATE	
                incomes_category_assigned_to_users	
            SET	
                incomes_category_assigned_to_users.name = :category
            WHERE 
                incomes_category_assigned_to_users.id = :id';
        
        $db = static::getDB();
        
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':category', $data["category_name"], PDO::PARAM_STMT);
        $stmt->bindValue(':id', $data["categoryId"], PDO::PARAM_INT);

        if($stmt->execute()) return true;
        
    }

    /**
     * Delete income category 
     * 
     * @param bool 
     */
    public static function deleteCategory($data)
    {  
        $sql = 
            'DELETE FROM 
                incomes_category_assigned_to_users 
            WHERE 
                incomes_category_assigned_to_users.id = :id';
        
        $db = static::getDB();
        
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $data["categoryId"], PDO::PARAM_INT);

        if($stmt->execute()) return true;
    }

    /**
     * Add income category 
     * 
     * @param bool 
     */
    public static function addCategory($data)
    {  
        $sql = 
            'INSERT INTO 
                incomes_category_assigned_to_users(user_id, name) 
            VALUES 
                (:id , :newCategory)';
        
        $db = static::getDB();
        
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':newCategory', $data["categoryName"], PDO::PARAM_STMT);
        $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);

        if($stmt->execute()) return true;
    }
    
}