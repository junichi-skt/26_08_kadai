<?php

//1. GETデータ取得
$newsId   = $_GET["news_id"];
$newsTitle = $_GET["news_title"];


// echo $newsId;
// echo $newsTitle;

//2. DB接続します(エラー処理追加)
include("functions.php");
$pdo = db_conn();


//３．データ削除SQL作成。bindValueさんはSQLインジェクション対策だよ。
$stmt = $pdo->prepare("DELETE FROM natalie_news_table WHERE news_id=:newsId");
$stmt->bindValue(':newsId', $newsId);
$status = $stmt->execute();

//４．データ登録処理後
$view ="";// 削除結果表示用に変数を設定しとくよ。
if($status==false){
  errorMsg($stmt);
}else{
  //５．index.phpへリダイレクト。exitはおまじないで処理終了。
  $view .= '<p>ニュースID：'.$newsId.'<br>';
  $view .= "タイトル：".$newsTitle.'<br>';
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
  <a href="menu.html">メニューに戻る</a>
</body>
</html>
