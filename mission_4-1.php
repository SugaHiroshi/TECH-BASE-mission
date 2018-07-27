<?php
$pdo = null;
header("Content-Type: text/html; charset=UTF-8");
$dsn = 'mysql:dbname=データベース名;host=localhost';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
?>
<html>
<head>
<meta http-equiv="content-type" charset="utf-8"><!--文字化け対策-->
<title>
mission4-1
</title>
</head>
<body>
<?php
$frag=0;//フラグ
$sql = 'SELECT * FROM mission4_1';//データベース読みだし
$results = $pdo -> query($sql);
$count=1;
foreach($results as $row){//現在の行数
	++$count;
}
if(!empty($_POST['hensyu'])&&$_POST['hensyu']!="編集する番号"){//編集前段階
	$sql = "SELECT * FROM mission4_1 where id = :id";//データベース
	$stmt = $pdo -> prepare($sql);//$results中身から
	$stmt->bindParam(":id", $i, PDO::PARAM_INT);//idをセット
	$i=$_POST['hensyu'];//編集先の配列番号を取得
	$stmt->execute();//実行
	$result = $stmt->fetch(PDO::FETCH_ASSOC);//配列として呼び出し
	$stmt->closeCursor();
	if($result['password']==$_POST['pass2']){//パスワード照合 内容セット
		$hdata1=$result['name'];
		$hdata2=$result['comment'];
		$hdata3=$result['password'];
	}
	else{
		echo "パスワードが違います！！<br />";
	}
	$frag = 1;//フラグ
}
else if(!empty($_POST['sakujo'])&&$_POST['sakujo']!="削除番号"){//削除
	$sql = "SELECT * FROM mission4_1 where id = :id";//データベース読みだし
	$stmt = $pdo -> prepare($sql);
	$stmt->bindParam(":id", $i, PDO::PARAM_INT);
	$i=$_POST['sakujo'];//編集先の配列番号を取得
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	if($result['password']==$_POST['pass3']){//パスワード照合
		$sql = 'SELECT * FROM mission4_1';//データベース読みだし
		$results = $pdo -> query($sql);
		foreach($results as $row){//表示
			if($row['id'] > $_POST['sakujo']&&$row['id'] < $count){//行つめ
				$id = $row['id']-1;
				$name = $row['name'];
				$comment = $row['comment'];
				$time =$row['time'];
				$password = $row['password'];
				$sql = "update mission4_1 set name= '$name' , comment = '$comment' ,time = '$time', password = '$password' where id = $id";
				$stmt = $pdo->query($sql);
			}
		}
		--$count;
		$sql = "delete from mission4_1 where id = $count"; //最後のデータ削除
		$result = $pdo->query($sql);
	}else{
		echo "パスワードが違います！！<br />";
	}
	$frag = 1;//フラグ
}else{
	$password = $_POST['pass1'];
	$sql = 'SELECT * FROM mission4_1 where password = :password';//データベース読みだし
	$stmt = $pdo -> prepare($sql);
	$stmt->bindParam(":password", $password, PDO::PARAM_STR);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	if(!empty($result)){//パスワード照合 パスワード重複確認
		$frag=2;//フラグ
	}
}
?>
<form action="mission_4-1.php" method="post"><!--フォームの設定-->
<input type="text" name="name" value="<?php if(!empty($hdata1)){echo $hdata1;}else{echo "名前";}?>"></label><!--名前入力フォーム-->
<br />
<input type="text" name="comment" value="<?php if(!empty($hdata2)){echo $hdata2;}else{echo "コメント";}?>"></label><!--文字入力フォーム-->
<br />
<input type="text" name="pass1" value="<?php if(!empty($hdata3)){echo "pass:".$hdata3;}else{echo "パスワード";}?>"></label><!--文字入力フォーム-->
<input type="submit" value="送信"><!--送信フォーム-->
<br /><br />
<input type="text" name="hensyu" value="編集する番号"></label><!--編集入力フォーム-->
<br /><input type="text" name="pass2" value="パスワード"></label><!--パスワード入力フォーム-->
<input type="submit" value="送信"><!--送信フォーム-->
<br /><br />
<input type="text" name="sakujo" value="削除番号"><!--削除入力フォーム-->
<br /><input type="text" name="pass3" value="パスワード"></label><!--パスワード入力フォーム-->
<input type="submit" value="送信"><!--送信フォーム-->
<br />
</from>
</body>
<?php
if($frag==1){}
else if($frag==2){
	echo "<br />すでにこのパスワードは使われています！！<br />";
}else{
	$pass = explode(":",$_POST['pass1']);//パスワード取りだし
	if(count($pass)==2){//編集
		$password =$pass[1]; 
		$name = $_POST['name'];
		$comment = $_POST['comment'];
		$time = date(":Y年m月d日 H時i分s秒");
		$sql = "update mission4_1 set name='$name', comment='$comment', time = '$time' where password = :password";//編集
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(":password", $password, PDO::PARAM_STR);
		$stmt->execute();
	}else if(!empty($_POST['name'])&&!empty($_POST['comment'])){
		$sql = 'SELECT * FROM mission4_1';//データベース読みだし
		$results = $pdo -> query($sql);
		$count=1;
		foreach($results as $row){//カウント
			++$count;
		}
		$sql = $pdo -> prepare("INSERT INTO mission4_1 (id,name, comment, time, password) VALUES ('$count',:name, :comment, :time, :password)");//新情報の獲得	
		$sql -> bindParam(':name', $name, PDO::PARAM_STR);
		$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
		$sql -> bindParam(':time', $time, PDO::PARAM_STR);
		$sql -> bindParam(':password', $password, PDO::PARAM_STR);
		$name = $_POST['name'];
		$comment = $_POST['comment']; 
		$time = date(":Y年m月d日 H時i分s秒");
		$password =$_POST['pass1']; 
		$sql -> execute();
	}
}
$sql = 'SELECT * FROM mission4_1';//データベース読みだし
$results = $pdo -> query($sql);
foreach($results as $row){//表示
	echo $row['id']." ".$row['name']." ".$row['comment']." ".$row['time']."pass:".$row['password']."<br />";//文字を表示
}
?>
