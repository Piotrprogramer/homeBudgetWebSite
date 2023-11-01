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
class PaymentMenager extends \Core\Model
{
    /**
     * Get payment category list
     * 
     * @param PDO::FETCH_ASSOC 
     */
    public static function getPaymentList($id)
    {
        $sql = 
        'SELECT name,id FROM 
            payment_methods_assigned_to_users 
        WHERE 
            payment_methods_assigned_to_users.user_id = :id
        ';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Update payment category 
     * 
     * @param bool 
     */
    public static function updateCategory($data)
    {  
        $sql = 
        'UPDATE	
            payment_methods_assigned_to_users	
        SET	
            payment_methods_assigned_to_users.name = :category
        WHERE 
            payment_methods_assigned_to_users.id = :id';
        
        $db = static::getDB();
        
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':category', $data["category_name"], PDO::PARAM_STMT);
        $stmt->bindValue(':id', $data["categoryId"], PDO::PARAM_INT);

        if($stmt->execute()) return true;
        
    }

    /**
     * Delete payment category 
     * 
     * @param bool 
     */
    public static function deleteCategory($data)
    {  
        $sql = 
            'DELETE FROM 
                payment_methods_assigned_to_users 
            WHERE 
            payment_methods_assigned_to_users.id = :id';
        
        $db = static::getDB();
        
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $data["categoryId"], PDO::PARAM_INT);

        if($stmt->execute()) return true;
    }

    /**
     * Add payment category 
     * 
     * @param bool 
     */
    public static function addCategory($data)
    {  
        $sql = 
            'INSERT INTO 
                payment_methods_assigned_to_users(user_id, name) 
            VALUES 
                (:id , :newCategory)';
        
        $db = static::getDB();
        
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':newCategory', $data["categoryName"], PDO::PARAM_STMT);
        $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);

        if($stmt->execute()) return true;
    }
}