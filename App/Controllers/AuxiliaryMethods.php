<?php

namespace App\Controllers;

/**
 * Account controller
 *
 * PHP version 7.0
 */
class AuxiliaryMethods extends \Core\Controller
{
    public static function upperCaseFirstLetter($word){
        $word = strtolower($word);
        return ucfirst($word);
    }
}
