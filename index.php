<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ニュース取得</title>
</head>
<body>
<h3>ニュース取得</h3>
<form method="post" action="scraping.php">

    <p>取得するニュースのジャンルを選択してください</p>
    <select name="pagetype">
      <option value="music">音楽</option>
      <option value="comic">コミック</option>
      <option value="owarai">お笑い</option>
      <option value="eiga">映画</option>
      <option value="stage">ステージ</option>
    </select><br>
    <br>
     <input type="submit" value="実行">

</form>
<br>
<br>
<a href="menu.html">メニューに戻る</a>


</body>
</html>
