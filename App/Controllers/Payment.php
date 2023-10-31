<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\PaymentMenager;
use \App\Auth;
use \App\Flash;

/**
 * Login controller
 *
 * PHP version 7.0
 */
class Payment extends Authenticated
{

    public static function getPayment()
    {
        if (isset($_SESSION['user_id'])) {
            echo json_encode(PaymentMenager::getPaymentList($_SESSION['user_id']));
        }
    }

    public function updateCategoryAction()
    {
        if (isset($_SESSION['user_id'])) {
            if (PaymentMenager::updateCategory($_POST))
                echo json_encode('all good');
        }
    }

    public function deleteCategoryAction()
    {
        if (isset($_SESSION['user_id'])) {
            if (PaymentMenager::deleteCategory($_POST))
                echo json_encode('all good');
        }
    }

    public function addCategoryAction()
    {
        if (isset($_SESSION['user_id'])) {
            if (PaymentMenager::addCategory($_POST)) {
                echo json_encode('all good');
            }
        }
    }
}