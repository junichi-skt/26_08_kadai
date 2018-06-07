<?php
//入力チェック(受信確認処理追加)
if(
  !isset($_POST["newsId"]) || $_POST["newsId"]=="" ||
  !isset($_POST["newsTitle"]) || $_POST["newsTitle"]==""||
  !isset($_POST["newsContents"]) || $_POST["newsContents"]==""
){
  exit('ParamError');
}

//1. POSTデータ取得
$newsId   = $_POST["newsId"];
$newsTitle   = $_POST["newsTitle"];
$newsContents  = $_POST["newsContents"];

//2. DB接続します(エラー処理追加)
include("functions.php");
$pdo = db_conn();


//３．データ登録SQL作成。bindValueさんはSQLインジェクション対策だよ。
$stmt = $pdo->prepare("UPDATE natalie_news_table SET news_id=:id, news_title=:title, news_contents=:contents WHERE news_id=:id");
$stmt->bindValue(':id', $newsId);
$stmt->bindValue(':title', $newsTitle);
$stmt->bindValue(':contents', $newsContents);
$status = $stmt->execute();

//４．データ登録処理後
$view = "";
if($status==false){
  errorMsg($stmt);
}else{
  $view .= '<p>ニュースID：'.$newsId.'<br>';
  $view .= "タイトル：".$newsTitle.'<br>';
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
     <label>ＩＤ：<input type="text" name="newsId" value="<?= $newsId?>" readonly></label><br>
     <label>記事：<textArea name="newsTitle" rows="1" cols="100" readonly><?= $newsTitle?></textArea></label><br>
     <label>記事：<textArea name="newsContents" rows="10" cols="100" readonly><?= $newsContents?></textArea></label><br>
    </fieldset>
  </div>
</form>
  <br>
  <a href="menu.html">メニューに戻る</a>
</body>
</html>