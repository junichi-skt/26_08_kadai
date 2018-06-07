<?php
//1.  DB接続します
try {
  $pdo = new PDO('mysql:dbname=gs_db;charset=utf8;host=localhost','root','');
} catch (PDOException $e) {
  exit('DbConnectError:'.$e->getMessage());
}

//２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT news_type,count(news_type) FROM natalie_news_table GROUP BY news_type");
$status = $stmt->execute();

//３．データ表示
$view="";
if($status==false) {
    //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit('sqlError:'.$error[2]);

}else{
  //Selectデータの数だけ自動でループしてくれる
  //FETCH_ASSOC=http://php.net/manual/ja/pdostatement.fetch.php
  //演算子.=を使うのはwhile処理でどんどん変数に加えていくから。
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){ 
    $view .= '<tr><td>'.$result["news_type"].'</td>';
    $view .= '<td>'.$result["count(news_type)"].'</td><tr>';
  }
}
 
// //結果セットを解放
// $result->free();

?>

<!DOCTYPE html>
<html lang="jp">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
</head>
<body>
        <h3>DB取得済みニュース集計情報</h3>

        <table border="1">
                <tr>
                        <th>ニュースカテゴリー</th>
                        <th>ニュース件数</th>
                </tr>
                <tr>
                        <?=$view?>
                </tr>
        </table>

</body>
</html>