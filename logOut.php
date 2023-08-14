<?php

require 'includes/init.php';

session_start();

Auth::logout();

Url::redirect('/');
