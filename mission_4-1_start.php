<?php
$dsn = 'mysql:dbname=tt_131_99sv_coco_com;host=localhost';
$user = 'tt-131.99sv-coco';
$password = 'y3YhAVHv';
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