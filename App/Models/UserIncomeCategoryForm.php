<?php

require_once 'IncomeMenager.php';

$income = IncomeMenager::incomeAsignetToUser();

echo json_encode($income);