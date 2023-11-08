<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\PaymentMenager;
use \App\Controllers\AuxiliaryMethods;
use \App\Flash;

/**
 * Login controller
 *
 * PHP version 7.0
 */
class Payment extends Authenticated
{

    /**
     * Get payment category from server 
     *
     * @return string JSON_encode 
     */
    public static function getPayment()
    {
        if (isset($_SESSION['user_id'])) {
            echo json_encode(PaymentMenager::getPaymentList($_SESSION['user_id']));
        }
    }

    /**
     * Update payment category to server 
     *
     * @return string JSON_encode 
     */
    public function updateCategoryAction()
    {
        $_POST["category_name"] = AuxiliaryMethods::upperCaseFirstLetter($_POST["category_name"]);
        if (isset($_SESSION['user_id'])) {
            if (PaymentMenager::updateCategory($_POST))
                echo json_encode('all good');
        }
    }

    /**
     * Delete payment category from server 
     *
     * @return string JSON_encode 
     */
    public function deleteCategoryAction()
    {
        if (isset($_SESSION['user_id'])) {
            if (PaymentMenager::deleteCategory($_POST))
                echo json_encode('all good');
        }
    }

    /**
     * Add payment category to server 
     *
     * @return string JSON_encode 
     */
    public function addCategoryAction()
    {
        $_POST["categoryName"] = AuxiliaryMethods::upperCaseFirstLetter($_POST["categoryName"]);
        if (isset($_SESSION['user_id'])) {
            
            if(PaymentMenager::isAvailable($_SESSION['user_id'], $_POST["categoryName"])){
                
                if (PaymentMenager::addCategory($_POST)) {
                    echo json_encode(true);
                } else echo json_encode(false);
            } else echo json_encode(false);

        }else echo json_encode(false);
    }

    /**
     * if category available return true, otherwise false in JSON encode format
     *
     * @return string JSONencode
     */
    public function isCategoryAvailable()
    {
        $data = json_encode($_POST);
        $object = json_decode($data);
        
        $category = array(
            'category_name' => $object->category_name,
        );

        $category_name = AuxiliaryMethods::upperCaseFirstLetter($category['category_name']);
 
        if (PaymentMenager::isAvailable($_SESSION['user_id'], $category_name)) echo json_encode(true);
 
        else echo json_encode(false);
    }
}