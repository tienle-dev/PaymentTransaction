<?php
session_start();
include 'db.php';
include 'header.php';

if (!isset($_SESSION['accno'])) {
    header("Location: login.php");
    exit();
}

$accno = $_SESSION['accno'];

// Lấy thông tin người dùng
$sql = "SELECT u.AccNo, u.Name, u.Address, u.Email, c.Pass, b.Balance, b.Interest 
        FROM userinfo u 
        JOIN balance b ON u.AccNo = b.AccNo 
        JOIN credentials c ON u.AccNo = c.AccNo 
        WHERE u.AccNo = ?";
$stmt = sqlsrv_query($conn, $sql, array($accno));
$user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

// Xử lý cập nhật thông tin
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = $_POST['password']; // có thể rỗng

    // Cập nhật userinfo
    $updateUser = "UPDATE userinfo SET Name = ?, Address = ?, Email = ? WHERE AccNo = ?";
    sqlsrv_query($conn, $updateUser, array($name, $address, $email, $accno));

    // Nếu password được nhập → hash và update
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $updatePass = "UPDATE credentials SET Pass = ? WHERE AccNo = ?";
        sqlsrv_query($conn, $updatePass, array($hashedPassword, $accno));
    }

    // Refresh lại thông tin sau khi update
    header("Location: account_info.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Account Info</title>
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
            <h3 class="mb-4">Account Information</h3>
            <p><strong>Account Number:</strong> <?php echo htmlspecialchars($user['AccNo']); ?></p>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['Name']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($user['Address']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['Email']); ?></p>
            <p><strong>Balance:</strong> <?php echo number_format($user['Balance']); ?></p>
            <p><strong>Interest:</strong> <?php echo number_format($user['Interest']); ?></p>
            <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
            <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Account Info</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['Name']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Address</label>
            <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($user['Address']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['Email']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">New Password (leave blank if no changes)</label>
            <input type="password" name="password" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save changes</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
