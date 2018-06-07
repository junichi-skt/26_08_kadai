<?php
//入力チェック(受信確認処理追加)
if(
  !isset($_GET["id"]) || $_GET["id"]=="" ||
  !isset($_GET["name"]) || $_GET["name"]=="" ||
  !isset($_GET["lid"]) || $_GET["lid"]=="" ||
  !isset($_GET["lpw"]) || $_GET["lpw"]=="" ||
  !isset($_GET["kanri_flg"]) || $_GET["kanri_flg"]=="" ||
  !isset($_GET["life_flg"]) || $_GET["life_flg"]==""
){
  exit('ParamError');
}

//1. GETデータ取得
$id   = $_GET["id"];
$name   = $_GET["name"];
$lid = $_GET["lid"];
$lpw = $_GET["lpw"];
$kanri_flg = $_GET["kanri_flg"];
$life_flg = $_GET["life_flg"];

// //2. DB接続します(エラー処理もあり)
// include("functions.php");
// $pdo = db_conn();


// //３．データ削除SQL作成。bindValueさんはSQLインジェクション対策だよ。
// $stmt = $pdo->prepare("DELETE FROM natalie_news_table WHERE news_id=:newsId");
// $stmt->bindValue(':newsId', $newsId);
// $status = $stmt->execute();

// //４．データ登録処理後
// $view ="";// 削除結果表示用に変数を設定しとくよ。
// if($status==false){
//   errorMsg($stmt);
// }else{
//   //５．index.phpへリダイレクト。exitはおまじないで処理終了。
//   $view .= '<p>ニュースID：'.$newsId.'<br>';
//   $view .= "タイトル：".$newsTitle.'<br>';
//   $view .= "のDB削除が完了したよ</p>";
//   // header("Location: select.php");
//   // exit;
// }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>アカウントの編集</title>
</head>
<body>

<form method="post" action="account_update.php">
  <div class="jumbotron">
   <fieldset>
    <legend>アカウントの編集</legend>
     <label>ID：<input type="text" name="id" value="<?= $id?>" readonly></label><br>
     <label>アカウント名：<input type="text" name="name" value="<?= $name?>"></label><br>     
     <label>ログインID：<input type="text" name="lid" value="<?= $lid?>"></label><br>
     <label>ログインPW：<input type="text" name="lpw" value="<?= $lpw?>"></label><br>
     <label>アカウント種別：<input type="text" name="kanri_flg" value="<?= $kanri_flg?>" readonly></label><br>
     <label>アカウント利用状況：<input type="text" name="life_flg" value="<?= $life_flg?>" readonly></label><br>
     <br>
     <input type="submit" value="更新する">
    </fieldset>
  </div>
</form>
  <br>
  <a href="account_menu.html">アカウントメニューに戻る</a>
</body>
</html>
