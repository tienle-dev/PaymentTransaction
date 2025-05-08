<?php
session_start();
include 'db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Insert vào credentials trước để lấy AccNo mới
    $sql = "INSERT INTO credentials (Pass) OUTPUT INSERTED.AccNo VALUES (?)";
    $stmt = sqlsrv_query($conn, $sql, array($password));
    if ($stmt && $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $accno = $row['AccNo'];

        // Insert vào userinfo
        $sql_userinfo = "INSERT INTO userinfo (AccNo, Name, Address, Email) VALUES (?, ?, ?, ?)";
        sqlsrv_query($conn, $sql_userinfo, array($accno, $name, $address, $email));

        // Insert vào balance
        $sql_balance = "INSERT INTO balance (AccNo, Balance, Interest) VALUES (?, 50, 0)";
        sqlsrv_query($conn, $sql_balance, array($accno));

        // Lưu accno vào session để login.php hiển thị thông báo
        $_SESSION['registered_accno'] = $accno;

        // Chuyển hướng sang login
        header("Location: index.php");
        exit();
    } else {
        $message = "Registration failed.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-body">
            <h3 class="text-center mb-4">Register New Account</h3>
            <?php if ($message): ?>
                <div class="alert alert-danger"><?php echo $message; ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Address</label>
                    <input type="text" name="address" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
                <p class="mt-3 text-center">Already have an account? <a href="index.php">Login here</a></p>
            </form>
        </div>
    </div>
</div>
</body>
</html>
