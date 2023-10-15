<?php

namespace App\Controllers;

use \Core\View;

/**
 * Signup controller
 *
 * PHP version 7.0
 */
class Test extends \Core\Controller
{

    /**
     * Show the signup success page
     *
     * @return void
     */
    public function testAction()
    {
        View::renderTemplate('Test/test.html');
    }
}
