<?php
$type = $_POST['type'];

if($type=='create1') {
	//DB接続
	$dsn = "";
	$user = "";
	$password = "";
	$pdo = new PDO($dsn,$user,$password);

	//書き込み
	$results = $pdo -> query("SELECT*FROM threads_test");
	$max=0;
	foreach($results as $row){
		if($max<$row["id"]){$max=$row["id"];}
	}
	$sql = $pdo -> prepare("INSERT INTO threads_test(id,title,body,created_at)VALUES(:id,:title,:body,:created_at)");
	$id = $max+1;
	$title =$_POST['title'];
	$body = $_POST['body'];
	$time = date("YmdHis");
	$sql -> bindParam(":id",$id,PDO::PARAM_STR);
	$sql -> bindParam(":title",$title,PDO::PARAM_STR);
	$sql -> bindParam(":body",$body,PDO::PARAM_STR);
	$sql -> bindParam(":created_at",date("Y-m-d H:i:s"),PDO::PARAM_STR);
	$sql -> execute();
	//スレッド画面に遷移
	header("Location: thread_top.php");
}
?>

<html>
<head>
<title>スレッド作成画面</title>
</head>
<body>
<form method="post" action="thread_new.php">
<table>
	<tr>
		<th>タイトル</th>
		<td><input type="text" name="title" /></td>
	</tr>
	<tr>
		<th>内容</th>
		<td><textarea name="body"></textarea></td>
	</tr>
	<tr>
		<td><input type="hidden" name="type" value="create1" /></td>
		<td><input type="submit" name="submit" value="作成" /></td>
	</tr>
</table>
</form>
</body>
</html>
