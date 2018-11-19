<?php
//DB接続
$dsn = "";
	$user = "";
	$password = "";
	$pdo = new PDO($dsn,$user,$password);

//スレッドIDを取得
$thread_id = $_GET['id'];
//スレッドを取得
$sql = "SELECT * FROM threads_test";
	$results = $pdo -> query($sql);
	foreach ($results as $row){
		if($row["id"]==$thread_id){
			$thread['created_at']=$row["created_at"];
			$thread['title']=$row["title"];
			$thread['body']=$row["body"];
			//echo $row["id"].",";
			//echo $row["title"].",";
			//echo $row["body"].",";
			//echo $row["created_at"]."<br>";
		}
	}
?>

<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php echo $thread['title'];?></title>
</head>
<body>
<p>作成日時:<?php echo $thread['created_at'];?></p>
<p>タイトル:<?php echo $thread['title'];?></p>
<p><?php echo $thread['body'];?></p>

<?php
	//表示機能
	$sql = "SELECT * FROM responses_test where thread_id = " . $thread_id;
	$results = $pdo -> query($sql);
	foreach ($results as $row){
		echo $row["id"].",";
		echo $row["name"].",";
		echo $row["body"].",";
		echo $row["created_at"];
		echo '<a href="res_new.php?id='.$thread_id.'&res_id='.$row["id"].'" >';
		echo "編集</a>";
		echo "<br/>";
	}
?>
<p><a href="res_new.php?id=<?php echo $thread_id;?>">書き込み</a></p>
</body>
</html>
