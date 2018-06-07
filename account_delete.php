<?php

//1. GETデータ取得
$id   = $_GET["id"];
$name   = $_GET["name"];
$lid = $_GET["lid"];
$lpw = $_GET["lpw"];
$kanri_flg = $_GET["kanri_flg"];
$life_flg = $_GET["life_flg"];


// echo $newsId;
// echo $newsTitle;

//2. DB接続します(エラー処理追加)
include("functions.php");
$pdo = db_conn();


//３．データ削除SQL作成。bindValueさんはSQLインジェクション対策だよ。
$stmt = $pdo->prepare("DELETE FROM gs_user_table WHERE id=:id");
$stmt->bindValue(':id', $id);
$status = $stmt->execute();

//４．データ登録処理後
$view ="";// 削除結果表示用に変数を設定しとくよ。
if($status==false){
  errorMsg($stmt);
}else{
  //５．index.phpへリダイレクト。exitはおまじないで処理終了。
  $view .= '<p>ID：'.$id.'<br>';
  $view .= "アカウント名：".$name.'<br>';
  $view .= "ログインID：".$lid.'<br>';
  $view .= "のDB削除が完了したよ</p>";
  // header("Location: select.php");
  // exit;
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

  <div class="delete-msg">
    <?=$view?>
  </div>
  <br>
  <br>
  <a href="account_menu.html">アカウントメニューに戻る</a>
</body>
</html>
