<?php
//DB接続
$dsn = "";
$user = "";
$password = "";
$pdo = new PDO($dsn,$user,$password);
$time=date("Y-m-d H:i:s");

$thread_id = $_GET['id'];
$res_id = $_GET['res_id']; 
if($res_id==""){
	$res_id=0;
}else{
	$sql = "SELECT * FROM responses_test where thread_id = " . $thread_id;
	$results = $pdo -> query($sql);
	foreach ($results as $row){
		if($row["id"]==$res_id){
			$change['name']=$row["name"];
			$change['body']=$row["body"];
			//echo $row["id"].",";
			//echo $row["title"].",";
			//echo $row["body"].",";
			//echo $row["created_at"]."<br>";
		}
	}
}
$comment=$_POST["body"];
$name=$_POST["name"];


$type = (isset($_POST['type']))? $_POST['type'] : null;

if($type=='create') {
	$thread_id = $_POST['id'];

	

	//書き込み機能
	
	$results = $pdo -> query("SELECT * FROM responses_test where thread_id = " . $thread_id);
	$max=0;
	foreach($results as $row){
		if($max<$row["id"]){$max=$row["id"];}
	}
	if($comment != "" && $name != "" ){
		$sql = $pdo -> prepare("INSERT INTO responses_test(id,thread_id,body,name,created_at)VALUES(:id,:thread_id,:body,:name,:created_at)");
		$n = $max+1;
		$sql -> bindParam(":id",$n,PDO::PARAM_STR);
		$sql -> bindParam(":thread_id",$thread_id,PDO::PARAM_STR);
		$sql -> bindParam(":name",$name,PDO::PARAM_STR);
		$sql -> bindParam(":body",$comment,PDO::PARAM_STR);
		$sql -> bindParam(":created_at",date("Y-m-d H:i:s"),PDO::PARAM_STR);
		$sql -> execute();
	}

	//スレッド画面に遷移
	header("Location: thread.php?id=" . $thread_id);
}
if($type=='remove') {
	
}
//編集機能
if($type=='change') {

	$res_id = $_POST["res_id"];
	$thread_id=$_POST["id"];
	$sql = "update responses_test set name='$name' ,body='$comment' ,created_at='$time' where id = $res_id AND thread_id = $thread_id";
	$result = $pdo -> query($sql);

	echo $name.",";
	echo $comment.",";
	echo $res_id.",";
	echo $thread_id;
//	スレッド画面に遷移
	header("Location: thread.php?id=" . $thread_id);
}
?>

<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>レス投稿画面</title>
</head>
<body>
<form method="post" action="res_new.php">
<table>
	<tr>
		<th>名前</th>
		<td><input type="text" name="name" value=<?php if($res_id==0){echo "name";}
								else{echo $change['name'];}?>></td>
	</tr>
	<tr>
		<th>内容</th>
		<td><input type="text" name="body" value=<?php if($res_id==0){echo "comm";}
								else{echo $change['body'];}?>></td>
	</tr>
	<tr>
		<td>
			<input type="hidden" name="id" value="<?php echo $thread_id;?>" />
			<input type="hidden" name="type" value=<?php if($res_id==0){echo "create";}
									else{echo "change";}?> />
			<input type="hidden" name="res_id" value="<?php echo $res_id;?>" />
		</td>
		<td><input type="submit" name="submit" value="投稿" /></td>
	</tr>
</table>
</form>
</body>
</html>
