<?php
	$dsn = "detabesename";
	$user = "username";
	$password = "password";
	$pdo = new PDO($dsn,$user,$password);
?>
<!DOCTYPE html>
<html>
    <head>
        <META charset ="UTF-8"></META>
    </head>
<?php
	$pass_W="cancan";
	//データを受け取って代入
	$comment=$_POST["comment"];
	$name=$_POST["name"];
	$rem=$_POST["rem"];
	$chan_s=$_POST["chan_s"];
	$chan_cat=$_POST["chan_cat"];
	$pass_r=$_POST["pass_r"];
	$pass_c=$_POST["pass_c"];
	$now_time=date("Y年m月d日H時i分s秒");

	//編集したい行の名前とコメントを切り取る
	$results = $pdo -> query("SELECT*FROM cantadeta");
	foreach($results as $row){
		if($chan_s == $row["id"]){
			$c_name=$row["name"];
			$c_comment=$row["comment"];//切り取って初期値にする
		}
	}
?>
	<h1>
	<?php
	if($pass_r != $pass_W && isset($pass_r)){echo "パスワードが違います";}
	if($pass_c != $pass_W && isset($pass_c)){echo "パスワードが違います";}
	if(isset($chan_bri) && $pass_W == $pass_c){echo "編集してください";}
	?>
	</h1>
	<form action="" method="post">
	<p>　　名前:<input type="text" name="name" 
		<?php if($pass_W == $pass_c){echo "value=".$c_name;}
			else {echo 'placeholder="名前"';}?>
	><br>
	コメント:<input type="text" name="comment" 
		<?php if($pass_W == $pass_c){echo "value=".$c_comment;}
			else {echo 'placeholder="コメント"';}?>
	>
	<input type="submit" value="送信">
	<input type="hidden" name="chan_cat" value="<?php if($pass_W == $pass_c){echo $chan_s;}?>">
	</p>
	</form>

	<form action="" method="post">
	<p>　　 削除:<input type="text" name="rem" placeholder="削除対象番号(半角)"><br>
	passward<input type="text" name="pass_r" placeholder="passward">
	<input type="submit" value="削除"></p>
	</form>

	<form action="" method="post">
	<p>　　 編集:<input type="text" name="chan_s" placeholder="編集対象番号(半角)"><br>
	passward<input type="text" name="pass_c" placeholder="passward">
	<input type="submit" value="編集"></p>
	</form>
<?php
	//書き込み機能
	$results = $pdo -> query("SELECT*FROM cantadeta");
	$max=0;
	$n=0;
	foreach($results as $row){
		$n=$n+1;
		if($max<$row["id"]){$max=$row["id"];}
	}
	if(isset($comment) && isset($name) &&$chan_cat == ""){
		$sql = $pdo -> prepare("INSERT INTO cantadeta(id,name,comment,time)VALUES(:id,:name,:comment,:time)");
		$id = $max+1;
		$sql -> bindParam(":id",$id,PDO::PARAM_STR);
		$sql -> bindParam(":name",$name,PDO::PARAM_STR);
		$sql -> bindParam(":comment",$comment,PDO::PARAM_STR);
		$sql -> bindParam(":time",$now_time,PDO::PARAM_STR);
		$sql -> execute();
	}


	//削除機能
	if(isset($rem)&&$pass_W == $pass_r){
		$id=$rem;
		$sql = "delete from cantadeta where id=$id";
		$result = $pdo->query($sql);
	}

	//編集機能
	if(isset($comment) && isset($name) &&isset($chan_cat)){
		$id = $chan_cat;
		$sql = "update cantadeta set name='$name' ,comment='$comment' ,time='$now_time' where id = $id";
		$result = $pdo -> query($sql);
	}
		
	//表示機能
	$results = $pdo -> query("SELECT*FROM cantadeta ORDER BY id DESC");
	$reverse=array();
		foreach($results as $row){
			array_unshift($reverse,$row["id"]." ".$row["name"]." ".$row["comment"]." ".$row["time"]);
		}
	//echo $row["id"]." ".$row["name"]." ".$row["comment"]." ".$row["time"]."<br>";
	foreach($reverse as $row){
		echo $row."<br>";
	}

?>
</html>

