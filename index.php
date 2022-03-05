<html>
<form action="" method="POST">
    <input type="file" name="file" />
    <input type="submit">
</form>
<?php

define('SERVER', 'localhost');
define('USERNAME', 'root');
define('PASSWORD', '');
define('DATABASE', 'rec');
$dbc = mysqli_connect(SERVER, USERNAME, PASSWORD, DATABASE) or die('Could not connect to the database');
$dbc_pdo = new PDO('mysql:host=' . SERVER . ';dbname=' . DATABASE, USERNAME, PASSWORD);

include("./ParseXLSX.php");
$file = isset($_REQUEST['file']) ?
    $_FILES['file']['temp_name'] :
    '';

$xlsx = new ParseXLSX('C:\Users\dadinho\Desktop\recon\reconciliation\INDIRECT TAX HOLDING.xlsx');

$rows = $xlsx->rows();
echo sizeof($rows);
// echo "<br>";
// echo count(current($rows));
echo '<br>';

$sql = "INSERT INTO `indirect_tax_holding` (`id`, `post_date`, `particulars`, `reference`, `value_date`, `debit`, `credit`, `balance`, `offset`) VALUES ";

for ($i = 1; $i <= sizeof($rows) - 1; $i++) {
    $record = $rows[$i];
    $record[0] = $record[0] == "" ? "1900-01-01" : $record[0];
    $record[1] = $record[1] == "" ? " " : mysqli_real_escape_string($dbc, $record[1]);
    $record[2] = $record[2] == "" ? " " : mysqli_real_escape_string($dbc, $record[2]);
    $record[3] = $record[3] == "" ? "1900-01-01" : $record[3];
    $record[4] = $record[4] == "" ? NULL : mysqli_real_escape_string($dbc, $record[4]);
    $record[5] = $record[5] == "" ? NULL : mysqli_real_escape_string($dbc, $record[5]);
    $record[6] = $record[6] == "" ? NULL : mysqli_real_escape_string($dbc, $record[6]);
    $sql .= "(NULL, '" . $record[0] . "', '" . $record[1] . "', '" . $record[2] . "', '" . $record[3] . "', '" . $record[4] . "','" . $record[5] . "', '" . $record[6] . "', '" . $record[7] . "'),";
}

$res = mysqli_query($dbc, rtrim($sql, ","));

if (!$res) {
    printf("Errormessage: %s\n", $dbc->error);
}
