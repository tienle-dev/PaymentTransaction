<?php
session_start();
if (!isset($_SESSION['accno'])) {
    header("Location: index.php");
    exit();
}
$accno = $_SESSION['accno'];
include 'header.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-body text-center">
            <h3>Welcome to your Dashboard</h3>
            <p>Choose a function below:</p>
            <div class="d-grid gap-3 col-6 mx-auto">
                <a href="account_info.php" class="btn btn-primary">Account Info</a>
                <a href="transfer.php" class="btn btn-success">Transfer Funds</a>
                <a href="transactions.php" class="btn btn-info">Transaction History</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
