<?php
//入力チェック(受信確認処理追加)
if(
  !isset($_POST["id"]) || $_POST["id"]=="" ||
  !isset($_POST["name"]) || $_POST["name"]=="" ||
  !isset($_POST["lid"]) || $_POST["lid"]=="" ||
  !isset($_POST["lpw"]) || $_POST["lpw"]=="" ||
  !isset($_POST["kanri_flg"]) || $_POST["kanri_flg"]=="" ||
  !isset($_POST["life_flg"]) || $_POST["life_flg"]==""
){
  exit('ParamError');
}

//1. GETデータ取得
$id   = $_POST["id"];
$name   = $_POST["name"];
$lid = $_POST["lid"];
$lpw = $_POST["lpw"];
$kanri_flg = $_POST["kanri_flg"];
$life_flg = $_POST["life_flg"];

//2. DB接続します(エラー処理追加)
include("functions.php");
$pdo = db_conn();


//３．データ登録SQL作成。bindValueさんはSQLインジェクション対策だよ。
$stmt = $pdo->prepare("UPDATE gs_user_table SET id=:id, name=:name, lid=:lid, lpw=:lpw WHERE id=:id");
$stmt->bindValue(':id', $id);
$stmt->bindValue(':name', $name);
$stmt->bindValue(':lid', $lid);
$stmt->bindValue(':lpw', $lpw);
$status = $stmt->execute();

//４．データ登録処理後
$view = "";
if($status==false){
  errorMsg($stmt);
}else{
  $view .= '<p>ID：'.$id.'<br>';
  $view .= "アカウント：".$name.'<br>';
  $view .= "ログインID：".$lid.'<br>';
  $view .= "のDB更新が完了したよ</p>";
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>

以下の内容でDBの更新が完了しました。
<form method="post" action="update.php">
  <div class="jumbotron">
   <fieldset>
    <legend>ニュース記事の編集</legend>
     <label>ID：<input type="text" name="id" value="<?= $id?>" readonly></label><br>
     <label>アカウント名：<input type="text" name="name" value="<?= $name?>" readonly></label><br>
     <label>ログインID：<input type="text" name="lid" value="<?= $lid?>" readonly></label><br>
    </fieldset>
  </div>
</form>
  <br>
  <a href="account_menu.html">アカウントメニューに戻る</a>
</body>
</html>