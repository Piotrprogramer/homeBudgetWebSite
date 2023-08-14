<?php

require 'config.php';
require 'Database.php';

//create data base ovject 
$db = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASS);
return $db->getConn();
