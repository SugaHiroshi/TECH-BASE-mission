<?php
$dsn = 'mysql:�f�[�^�x�[�X��;host=localhost';
$user = '���[�U�[��';
$password = '�p�X���[�h';
$pdo = new PDO($dsn,$user,$password);

$sql = "CREATE TABLE mission4_1"
."("
."id INT,"
."name char(32),"
."comment TEXT,"
."time TEXT,"
."password TEXT"
.");";

$stmt = $pdo->query($sql);

$sql = 'SHOW CREATE TABLE mission4_1';
$result = $pdo -> query($sql);
foreach($result as $row){
	print_r($row);
}
echo "<hr>";

?>