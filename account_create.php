<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>新規アカウント作成</title>
</head>
<body>


<form method="post" action="account_create_2.php">
  <div>
   <fieldset>
    <legend>新規アカウント作成</legend>
     <label>アカウント名：<input type="text" name="name"></label><br>
     <label>ログインID：<input type="text" name="lid"></label><br>
     <label>ログインPW：<input type="text" name="lpw"></label><br>
     <label>アカウント種別：
      <select name="kanri_flg">
      <option value="0">管理者</option>
      <option value="1">スーパー管理者</option>
      </select><br>
    </label><br>
     <br>
     <input type="submit" value="登録">
    </fieldset>
  </div>
</form>

  <br>
  <a href="account_menu.html">アカウントメニューに戻る</a>

</body>
</html>
