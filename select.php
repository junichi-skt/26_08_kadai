<?php

//1.  DB接続します
include("functions.php");
$pdo = db_conn();

//２．データ登録SQL作成
$pageType = $_POST["pagetype"];
$newsWord = $_POST["newsword"];
$stmt = $pdo->prepare("SELECT * FROM natalie_news_table WHERE news_type = '$pageType' AND news_contents LIKE '%$newsWord%' ORDER BY news_date DESC LIMIT 10");
$status = $stmt->execute();

//３．データ表示
$view="";
if($status==false) {
  errorMsg($stmt);
}else{
  //Selectデータの数だけ自動でループしてくれる
  //FETCH_ASSOC=http://php.net/manual/ja/pdostatement.fetch.php
  //演算子.=を使うのはwhile処理でどんどん変数に加えていくから。
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){ 
    $view .= '<div class="article"><p class="news-date">'.$result["news_date"].'</p><br>';
    $view .= '<p class="news-title">'.$result["news_title"].'</p><br>';
    $view .= '<p class="news-contents">'.$result["news_contents"].'</p><br>';
    $view .= '<a href="edit.php?news_id='.$result["news_id"].'&news_title='.$result["news_title"].'&news_contents='.$result["news_contents"].'">';
    $view .= '[記事を編集]';
    $view .= '</a>　';
    $view .= '<a href="delete.php?news_id='.$result["news_id"].'&news_title='.$result["news_title"].'">';
    $view .= '[記事を削除]';
    $view .= '</a>';
    $view .= '</div><hr>';
  }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>検索結果表示</title>
  <!-- <link rel="stylesheet" href="./css/reset.css"> -->
  <link rel="stylesheet" href="./css/style.css"> 
</head>

<div>
<h3>ニュース検索・編集</h3>
<p>検索結果表示(最大表示件数：最新10件までだよ)</p>

  <input id ="read" type="BUTTON" value="タイトル音声読み上げ">
  <br>
  <br>
  <div id="search-result"><?=$view?></div>
</div>

  <br>
  <a href="select.html">検索画面に戻る</a>

<script src="http://code.jquery.com/jquery-3.2.1.js"></script>
<script src="./js/yomiagechan.js"></script>

</body>
</html>