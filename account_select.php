<?php
//1.  DB接続します
try {
  $pdo = new PDO('mysql:dbname=gs_db;charset=utf8;host=localhost','root','');
} catch (PDOException $e) {
  exit('DbConnectError:'.$e->getMessage());
}

//２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT * FROM gs_user_table");
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
    $view .= '<tr><td>'.$result["id"].'</td>';
    $view .= '<td>'.$result["name"].'</td>';
    $view .= '<td>'.$result["lid"].'</td>';
    $view .= '<td>'.$result["lpw"].'</td>';
    $view .= '<td>'.$result["kanri_flg"].'</td>';
    $view .= '<td>'.$result["life_flg"].'</td>';
    $view .= '<td>';
    $view .= '<a href="account_edit.php?id='.$result["id"].'&name='.$result["name"].'&lid='.$result["lid"].'&lpw='.$result["lpw"].'&kanri_flg='.$result["kanri_flg"].'&life_flg='.$result["life_flg"].'">';
    $view .= '[編集]';
    $view .= '</a>　';
    $view .= '<a href="account_delete.php?id='.$result["id"].'&name='.$result["name"].'&lid='.$result["lid"].'&lpw='.$result["lpw"].'&kanri_flg='.$result["kanri_flg"].'&life_flg='.$result["life_flg"].'">';
    $view .= '[削除]';
    $view .= '</a></td></tr>';
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
        <title>アカウント一覧・編集</title>
</head>
<body>
        <h3>アカウント一覧・編集</h3>

        <table border="1">
                <tr>
                        <th>ID</th>
                        <th>アカウント名</th>
                        <th>ログインID</th>
                        <th>ログインPW</th>
                        <th>アカウント種別</th>
                        <th>アカウント利用状況</th>
                        <th>アカウント編集</th>
                </tr>
                <tr>
                        <?=$view?>
                </tr>
                <br>
        </table>
        <br>
        <br>
  <a href="account_menu.html">アカウントメニューに戻る</a>
</body>
</html>