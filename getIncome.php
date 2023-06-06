<?php
// Connect to database
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=homebudget', 'root', '');

// Query database
$stmt = $pdo->query(
$_SESSION['income_qry']);

// Fetch data
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>

