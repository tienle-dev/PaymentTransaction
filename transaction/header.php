<?php
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['accno'])) {
    header("Location: index.php");
    exit();
}
include 'db.php';

// Lấy tên người dùng từ bảng userinfo
$sql = "SELECT b.Balance, u.Name FROM userinfo u JOIN balance b ON b.AccNo = u.AccNo WHERE u.AccNo = ?";
$stmt = sqlsrv_query($conn, $sql, array($_SESSION['accno']));
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$name = $row && $row['Name'] ? $row['Name'] : 'User #' . $_SESSION['accno'];
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">🏦 MyBank</a>
        <div class="d-flex align-items-center">
            <span class="me-3"><strong>Balance:</strong> <?php echo number_format($row['Balance']); ?></span>
            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="avatar" width="40" class="rounded-circle me-2">
            <span class="me-3"><?php echo htmlspecialchars($name); ?></span>
            <a href="logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
        </div>
    </div>
</nav>
