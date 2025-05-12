<?php
session_start();
if (!isset($_SESSION['accno'])) {
    header("Location: index.php");
    exit();
}
$accno = $_SESSION['accno'];
include 'db.php';
include 'header.php';

if (isset($_POST['submit'])) {
    $receiver = $_POST['receiver'];
    $amount = $_POST['amount'];
    $remarks = $_POST['remarks'];

    // Get sender balance
    $sql_sender = "SELECT Balance FROM balance WHERE AccNo = ?";
    $stmt_sender = sqlsrv_query($conn, $sql_sender, array($accno));
    $row_sender = sqlsrv_fetch_array($stmt_sender, SQLSRV_FETCH_ASSOC);

    if ($row_sender && $row_sender['Balance'] >= $amount) {
        $new_sender_balance = $row_sender['Balance'] - $amount;

        $sql_rec = "SELECT Balance FROM balance WHERE AccNo = ?";
        $stmt_rec = sqlsrv_query($conn, $sql_rec, array($receiver));
        $row_rec = sqlsrv_fetch_array($stmt_rec, SQLSRV_FETCH_ASSOC);

        if ($row_rec) {
            $new_rec_balance = $row_rec['Balance'] + $amount;

            sqlsrv_query($conn, "UPDATE balance SET Balance = ? WHERE AccNo = ?", array($new_sender_balance, $accno));
            sqlsrv_query($conn, "UPDATE balance SET Balance = ? WHERE AccNo = ?", array($new_rec_balance, $receiver));

            sqlsrv_query($conn, "INSERT INTO transactions (Sender, Receiver, Amount, Remarks) VALUES (?, ?, ?, ?)",
                array($accno, $receiver, $amount, $remarks));

            $msg = "Transfer successful!";
        } else {
            $msg = "Receiver account not found.";
        }
    } else {
        $msg = "Insufficient balance.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Transfer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-body">
            <h3>Transfer Funds</h3>
            <?php if (isset($msg)) echo "<div class='alert alert-info'>$msg</div>"; ?>
            <form method="post">
                <div class="mb-3">
                    <label>Receiver Account No</label>
                    <input type="number" name="receiver" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Amount</label>
                    <input type="number" name="amount" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Remarks</label>
                    <input type="text" name="remarks" class="form-control" required>
                </div>
                <button type="submit" name="submit" class="btn btn-primary w-100">Transfer</button>
            </form>
            <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
        </div>
    </div>
</div>
</body>
</html>
