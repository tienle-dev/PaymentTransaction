<?php
session_start();
include 'db.php';

// Nếu đã đăng nhập, chuyển về dashboard
if (isset($_SESSION['accno'])) {
    header("Location: dashboard.php");
    exit();
}

$message = '';
if (isset($_SESSION['registered_accno'])) {
    $message = "Registration successful! Your Account Number (AccNo) is: " . $_SESSION['registered_accno'];
    unset($_SESSION['registered_accno']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accno = $_POST['accno'];
    $password = $_POST['password'];

    $sql = "SELECT Pass FROM credentials WHERE AccNo = ?";
    $stmt = sqlsrv_query($conn, $sql, array($accno));
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    if ($row && password_verify($password, $row['Pass'])) {
        $_SESSION['accno'] = $accno;
        header("Location: dashboard.php");
        exit();
    } else {
        $message = "Invalid Account Number or Password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
    <div class="card shadow-lg">
        <div class="card-body">
            <h3 class="text-center mb-4">Login</h3>
            <?php if ($message): ?>
                <div class="alert alert-info"><?php echo $message; ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="mb-3">
                    <label>Account Number (AccNo)</label>
                    <input type="number" name="accno" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
                <p class="mt-3 text-center">Don't have an account? <a href="register.php">Register here</a></p>
            </form>
        </div>
    </div>
    </div>
    </div>
</div>
</body>
</html>
