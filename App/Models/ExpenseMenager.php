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
    public static function getCategoryId($category)
    {
        $sql = 'SELECT id FROM expenses_category_assigned_to_users WHERE name = :category AND user_id = :user_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $_SESSION["user_id"], PDO::PARAM_INT);
        $stmt->bindValue(':category', $category, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $cat_id = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($cat_id != null)
                return $cat_id["id"];

        } else
            return false;
    }

    /**
     * 
     * @return int Return id payment assigned to user, 0 otherwise
     */
    public function getPaymentId()
    {
        $sql = 'SELECT 
                    id
                FROM 
                    payment_methods_assigned_to_users 
                WHERE 
                    user_id = :user_id
                AND
                    name = :payment';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $_SESSION["user_id"], PDO::PARAM_INT);
        $stmt->bindValue(':payment', $this->payment_method, PDO::PARAM_STR);

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
            $stmt->bindValue(':expense_category_assigned_to_user_id', $this->getCategoryId($this->Category), PDO::PARAM_STR);
            $stmt->bindValue(':payment_method_assigned_to_user_id', $this->getPaymentId(), PDO::PARAM_STR); //  MISSING
            $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
            $stmt->bindValue(':date_of_expense', $this->date, PDO::PARAM_STR);
            $stmt->bindValue(':expense_comment', $this->coment, PDO::PARAM_STR);

            return $stmt->execute();
        }
        return false;
    }

    /**
     * Getting data about user expense category
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
        $db = static::getDB();
        foreach ($categorys as &$value) {
            $sql =
                'INSERT INTO 
                expenses_category_assigned_to_users 
             VALUES
                (null, :user_id,:category, null)';

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


    /**
     * Check payment assigned to user is empty
     *
     * @return bool  if epmty, false otherwise
     */
    public static function isPaymentUserArray()
    {
        $sql = 'SELECT id, name FROM payment_methods_assigned_to_users WHERE user_id = :user_id';

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

    /**
     * Getting defoult payment method
     *
     * @return void  array with income category
     */
    public static function copyDefaultPaymentCategory()
    {
        $sql = 'SELECT name FROM payment_methods_default';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        if ($stmt->execute()) {

            $payment_category = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ExpenseMenager::pastDefaultPaymentCategory($payment_category);
        }
    }

    /**
     * Past defoult expense category to user category
     *
     * @return void  
     */
    public static function pastDefaultPaymentCategory($payment_category)
    {
        $sql =
            'INSERT INTO 
                payment_methods_assigned_to_users 
             VALUES
                (null, :user_id,:Cash),
                (null, :user_id,:Debit_card),
                (null, :user_id,:Credit_card)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':Cash', $payment_category[0]['name'], PDO::PARAM_STR);
        $stmt->bindValue(':Debit_card', $payment_category[1]['name'], PDO::PARAM_STR);
        $stmt->bindValue(':Credit_card', $payment_category[2]['name'], PDO::PARAM_STR);

        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

        $stmt->execute();
    }

    /**
     * Getting data about user payment category
     *
     * @return boolean  True if getted correctly, false otherwise
     */
    public static function paymentAsignetToUser()
    {
        $sql = 'SELECT id, name FROM payment_methods_assigned_to_users WHERE user_id = :user_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

        if ($stmt->execute()) {

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        }
    }

    /**
     * Getting expense list
     *
     * @return PDO::FETCH_ASSOC if correctly, false otherwise
     */
    public static function getExpenseList($id)
    {
        $sql =
            'SELECT name,id FROM 
            expenses_category_assigned_to_users 
        WHERE 
            expenses_category_assigned_to_users.user_id = :id
        ';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Update category list
     *
     * @return void
     */
    public static function updateCategory($data)
    {
        $sql =
            'UPDATE	
            expenses_category_assigned_to_users	
        SET	
            expenses_category_assigned_to_users.name = :category
        WHERE 
            expenses_category_assigned_to_users.id = :id';

        $db = static::getDB();

        $stmt = $db->prepare($sql);

        $stmt->bindValue(':category', $data["categoryName"], PDO::PARAM_STMT);
        $stmt->bindValue(':id', $data["categoryId"], PDO::PARAM_INT);

        if ($stmt->execute())
            return true;
    }

    /**
     * Delete expense category
     *
     * @return void
     */
    public static function deleteCategory($data)
    {
        $sql =
            'DELETE FROM 
                expenses_category_assigned_to_users 
            WHERE 
                expenses_category_assigned_to_users.id = :id';

        $db = static::getDB();

        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $data["categoryId"], PDO::PARAM_INT);

        $stmt->execute();
    }

    /**
     * Delete expense category
     *
     * @return void
     */
    public static function deleteExpenses($data)
    {
        $sql =
            'DELETE FROM 
                expenses
            WHERE
                expenses.expense_category_assigned_to_user_id = :id';

        $db = static::getDB();

        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $data["categoryId"], PDO::PARAM_INT);

        $stmt->execute();
    }

    /**
     * Add expense category
     *
     * @return void
     */
    public static function addCategory($data)
    {
        $sql =
            'INSERT INTO 
                expenses_category_assigned_to_users(user_id, name) 
            VALUES 
                (:id , :newCategory)';

        $db = static::getDB();

        $stmt = $db->prepare($sql);

        $stmt->bindValue(':newCategory', $data["categoryName"], PDO::PARAM_STMT);

        $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);

        if ($stmt->execute())
            return true;
    }

    /**
     * Set a expense limit
     *
     * @return void
     */
    public static function setLimit($data)
    {
        $sql =
            'UPDATE 
                expenses_category_assigned_to_users 
            SET 
                expenses_limit = :categoryLimit
            WHERE 
                expenses_category_assigned_to_users.user_id = :id
            AND
                expenses_category_assigned_to_users.name = :categoryName';

        $db = static::getDB();

        $stmt = $db->prepare($sql);

        $stmt->bindValue(':categoryLimit', $data["categoryLimit"], PDO::PARAM_STR);
        $stmt->bindValue(':categoryName', $data["categoryName"], PDO::PARAM_STR);
        $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);

        $stmt->execute();
    }

    /**
     * Remove a expense limit
     *
     * @return void
     */
    public static function removeLimit($data)
    {
        $sql =
            'UPDATE 
                expenses_category_assigned_to_users 
            SET 
                expenses_limit = null
            WHERE 
                expenses_category_assigned_to_users.user_id = :id
            AND
                expenses_category_assigned_to_users.name = :categoryName';

        $db = static::getDB();

        $stmt = $db->prepare($sql);

        $stmt->bindValue(':categoryName', $data["categoryName"], PDO::PARAM_STR);
        $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);

        $stmt->execute();
    }

    /**
     * Get expensse limit
     *
     * @return void 
     */
    public static function getLimit($userId, $category)
    {
        $sql =
            'SELECT 
                expenses_category_assigned_to_users.expenses_limit
            FROM 
                expenses_category_assigned_to_users
            WHERE 
                expenses_category_assigned_to_users.user_id = :userId
            AND 
                name = :category';

        $db = static::getDB();

        $stmt = $db->prepare($sql);

        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':category', $category, PDO::PARAM_STR);

        $stmt->execute();

        //return $stmt->fetch(PDO::FETCH_ASSOC);
        return $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_LAST);
    }

    /**
     * Check expensse category is available
     *
     * @return bool 
     */
    public static function isAvailable($id, $categoryName)
    {
        $sql =
            'SELECT 
            name 
        FROM 
            expenses_category_assigned_to_users 
        WHERE 
            expenses_category_assigned_to_users.user_id = :id
        AND
            expenses_category_assigned_to_users.name = :categoryName 
        ';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':categoryName', $categoryName, PDO::PARAM_STR);

        $stmt->execute();


        $word = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($word)) {
            return true;
        } else
            return false;
    }

    /**
     * Check expense category is assigned
     *
     * @return bool 
     */
    public static function isAssigned($id, $category_id)
    {
        $sql =
            'SELECT 
            expenses.id
        FROM
            expenses
        WHERE
            expenses.user_id = :id
        AND
            expenses.expense_category_assigned_to_user_id = :category_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':category_id', $category_id, PDO::PARAM_STR);

        $stmt->execute();

        $word = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($word)) {
            return true;
        } else
            return false;
    }
}