
<?php
$serverName = "localhost,1445"; // hoặc IP server và instance name
$connectionOptions = array(
    "Database" => "HETHONGTHANHTOAN",
    "Uid" => "sa", // tài khoản SQL Server
    "PWD" => "Password_123#", // mật khẩu SQL Server
    "CharacterSet" => "UTF-8"
);

$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>
