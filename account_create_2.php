<?php
//入力チェック(受信確認処理追加)
if(
  !isset($_POST["name"]) || $_POST["name"]=="" ||
  !isset($_POST["lid"]) || $_POST["lid"]=="" ||
  !isset($_POST["lpw"]) || $_POST["lpw"]=="" ||
  !isset($_POST["kanri_flg"]) || $_POST["kanri_flg"]==""
){
  exit('ParamError');
}

//1. POSTデータ取得
$name   = $_POST["name"];
$lid  = $_POST["lid"];
$lpw = $_POST["lpw"];
$kanriFlg = $_POST["kanri_flg"];

//2. DB接続します(エラー処理追加)
include("functions.php");
$pdo = db_conn();


//３．データ登録SQL作成。bindValueさんはSQLインジェクション対策だよ。
$stmt = $pdo->prepare("INSERT INTO gs_user_table (id, name, lid, lpw, kanri_flg,
life_flg )VALUES(NULL, :u1, :u2, :u3, :u4, 0)");
$stmt->bindValue(':u1', $name);
$stmt->bindValue(':u2', $lid);
$stmt->bindValue(':u3', $lpw);
$stmt->bindValue(':u4', $kanriFlg);
$status = $stmt->execute();

//４．データ登録処理後
$view = "";
if($status==false){
  errorMsg($stmt);
}else{
  $view .= '<p>アカウント名：'.$name.'<br>';
  $view .= "ログインID：".$lid.'<br>';
  $view .= "の新規アカウント登録が完了したよ</p>";
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

以下の内容で新規アカウントの発行が完了しました。
  <div class="jumbotron">
   <fieldset>
    <legend>発行アカウント情報</legend>
      <?= $view ?>
    </fieldset>
  </div>
  <br>
  <br>
  <a href="account_menu.html">アカウントメニューに戻る</a>
</body>
</html>
