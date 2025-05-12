<?php
session_start();
include 'db.php';
include 'header.php';

if (!isset($_SESSION['accno'])) {
    header("Location: login.php");
    exit();
}

$accno = $_SESSION['accno'];

// Lấy tên người dùng
$sql = "SELECT Name FROM userinfo WHERE AccNo = ?";
$stmt = sqlsrv_query($conn, $sql, array($accno));
$user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

// Lấy danh sách giao dịch
$sql = "SELECT * FROM transactions 
        WHERE Sender = ? OR Receiver = ? 
        ORDER BY DateTime DESC";
$stmt = sqlsrv_query($conn, $sql, array($accno, $accno));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Transactions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #6c757d;
            display: inline-block;
            text-align: center;
            color: white;
            line-height: 40px;
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-light">

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h3 class="mb-4">Transaction History</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sender</th>
                        <th>Receiver</th>
                        <th>Amount</th>
                        <th>Remarks</th>
                        <th>DateTime</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $row['Sender']; ?></td>
                        <td><?php echo $row['Receiver']; ?></td>
                        <td><?php echo number_format($row['Amount']); ?></td>
                        <td><?php echo htmlspecialchars($row['Remarks']); ?></td>
                        <td><?php echo $row['DateTime']->format('Y-m-d H:i:s'); ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
            <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
        </div>
    </div>
</div>
</body>
</html>
