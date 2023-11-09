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
        $sql = 
        'SELECT 
            id 
        FROM 
            incomes_category_assigned_to_users 
        WHERE 
            name = :category 
        AND 
            user_id = :user_id';

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
        $sql = 
        'SELECT 
            id, name 
        FROM 
            incomes_category_assigned_to_users 
        WHERE 
            user_id = :user_id';

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
        $db = static::getDB(); 
        foreach ($categorys as &$value) {
            $sql =
            'INSERT INTO 
                incomes_category_assigned_to_users 
             VALUES
                (null, :user_id,:category)';

             $stmt = $db->prepare($sql);    

             $stmt->bindValue(':category', $value['name'], PDO::PARAM_STR);
             $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
     
             $stmt->execute();
        }    
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
        $sql = 
        'SELECT 
            id, name 
        FROM 
            incomes_category_assigned_to_users 
        WHERE 
            user_id = :user_id';

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
     * Delete assignet incomes 
     * 
     * @param bool 
     */
    public static function deleteIncomes($data)
    {  
        $sql = 
            'DELETE FROM
                incomes
            WHERE
                incomes.income_category_assigned_to_user_id = :id';
        
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

    /**
     * Check income category is available
     *
     * @return bool 
     */
    public static function isAvailable($id, $category_name)
    {
        $sql = 
        'SELECT 
            name 
        FROM 
            incomes_category_assigned_to_users 
        WHERE 
            incomes_category_assigned_to_users.user_id = :id
        AND
            incomes_category_assigned_to_users.name = :category_name 
        ';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':category_name', $category_name, PDO::PARAM_STR);

        $stmt->execute();


        $word = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(empty($word)){
            return true;
        }
        else return false;
    }
    
    /**
     * Check income category is available
     *
     * @return bool 
     */
    public static function isAssigned($id, $category_id)
    {
        $sql = 
        'SELECT 
            incomes.id 
        FROM 
            incomes
        WHERE
            incomes.user_id = :id
        AND
        incomes.income_category_assigned_to_user_id = :category_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':category_id', $category_id, PDO::PARAM_STR);

        $stmt->execute();

        $word = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(empty($word)){
            return true;
        }
        else return false;
    }
}