<?php

// ジャンル選択データが飛ばされてこない時の対応
if(!isset($_POST["pagetype"]) ||$_POST["pagetype"] == ""){
	exit('ParamError');
  }else{
	  $pageType = $_POST["pagetype"];
  }


// 選ばれたジャンルのスクレイピング処理。まず、phpQueryを読む。
require_once('phpQueryAllInOne.php');

// 選ばれたジャンルのトップニュースたちのURLを読み込む関数。
function scrape_urls() {
	$pageType = $_POST["pagetype"];
	$format = "https://natalie.mu/%s/news";
	$htmlBase = sprintf($format,$pageType);
	$html = file_get_contents($htmlBase);
	$obj  = phpQuery::newDocument($html);
	$article  = $obj->find(".GAE_latestNews > .NA_articleList");
	$contents = $article['a'];

	$urls = [];
	foreach( $contents as $content ){
		$urls[] =  $content->getAttribute('href');
	}
	return $urls;
}

// ニュースのIDと配信日とタイトルと記事をスクレイピングする関数
function get_ids($urls) {
	$newsIds = [];
	foreach ( $urls as $url ) {
		$html  = file_get_contents($url);
		$obj   = phpQuery::newDocument($html);
		$newsDate = $obj->find(".NA_articleHeader > .NA_attr > p")->text();
		$title = $obj->find(".NA_articleHeader > h1")->text();
		$contents = $obj['.NA_articleBody > p']->text();

		//ナタリーさん広告ページ(＝タイトル取れない)ときは処理しないように。
		if($title != null){
			preg_match("/\d{6}/", $url, $matches);
			$newsId = $matches[0];

			array_push($newsIds,$newsId);
		}
	}
	return $newsIds;
}

// スクレイピングしたニュース情報をDB格納する関数
function upinsert($newsId, $newsDate, $pageType, $title, $contents) {
//DB接続(root以降はDBのユーザー名とパスワード)
	try {
		$pageType = $_POST["pagetype"];
		$dbh  = new PDO('mysql:dbname=gs_db;charset=utf8;host=localhost','root','');
		$stmt = $dbh-> query("SET NAMES utf8;");
			$stmt = $dbh->prepare('
			INSERT INTO natalie_news_table (news_id,news_date,news_type,news_title,news_contents,created_at,updated_at)
			VALUES( :a1, :a2, :a3, :a4, :a5, now(), now())
			ON DUPLICATE KEY UPDATE news_title = :a4, news_contents = :a5, updated_at = now()');
			$stmt->bindValue('a1', $newsId, PDO::PARAM_INT);  //Integer（文字列：PARAM_STR/数値の場合 PDO::PARAM_INT)
			$stmt->bindValue('a2', $newsDate, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
			$stmt->bindValue('a3', $pageType, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
			$stmt->bindValue('a4', $title, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
			$stmt->bindValue('a5', $contents, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
			$stmt->execute();//executeでSQL実行だよー。
			// echo $newsId;
			// echo '<br>';
			// // echo $newsDate;
			// // echo '<br>';
			// echo $title;
			// echo '<br>';
			// echo $contents;
			// echo '<br>';
			// print_r($stmt->errorInfo());
	} catch (PODException $e) {
		exit('DbConnectError:'.$e->getMessage());
  }
}

// スクレイピングしてDB格納したニュースのなかから最新4件ピックアップしてAlexaスキルのお作法どおりのJSONにする関数
function build_json() {
	$dbh  = new PDO('mysql:dbname=gs_db;charset=utf8;host=localhost','root','');
	$stmt = $dbh-> query("SET NAMES utf8;");
	$stmt = $dbh->query("SELECT * FROM natalie_news_table ORDER BY news_date DESC limit 4");

	$res  = [];
	foreach ($stmt as $row) {
		// print($row['news_contents']);
		$newsDateJson = str_replace(" ","T",$row['news_date']);
		array_push(
			$res,
			array(
				'uid'			 => $row['news_id'],
				'titleText'		 => $row['news_title'],
				'updateDate'	 => $newsDateJson.".0Z",
				// 'updateDate'	 => "2018-05-29T00:00:00.0Z",
				// 'updateDate'	 => $row['news_date'].".0Z",
				'mainText'		 => $row['news_contents'],
				'redirectionUrl' => "https://natalie.mu/".$row['news_type']."/news/".$row['news_id']
			)
		);
	}

	$json = json_encode($res, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
	return $json;
}


// build_json関数で作ったJSONファイルをWEBに転送してAlexaさんが読めるように同期する関数。
// pemさんを配置してない環境では転送できませんよー。
function scp_json($file) {
	// echo "hoge";
	#$cmd = ‘cp test.php test2.php’;
	$cmd = "scp -o 'StrictHostKeyChecking=no' -i ./pem/sekita.pem ./gs.json ec2-user@54.91.175.119:/usr/share/nginx/html/";
	$array = [];
	if ( !exec("$cmd 2>&1",$array)) {
	   //command失敗を検知して処理したい
	   echo "NG";
	}
	var_dump($array);
	}


// スクレイピングするURLたちを変数に格納して、それぞれの中身をスクレイピングしたら、情報をDBに格納するよー。
$urls = scrape_urls();
$newsIds  = get_ids($urls);
foreach( $newsIds as $newsId ) {
	$pageType = $_POST["pagetype"];
	$html	  = file_get_contents(sprintf("https://natalie.mu/%s/news/%s", $pageType , $newsId));
	$obj	  = phpQuery::newDocument($html);
	$str = $obj->find(".NA_articleHeader > .NA_attr > p")->text();
	$title = $obj->find(".NA_articleHeader > h1")->text();
	$contents = $obj['.NA_articleBody > p']->text();
	$search = array("年","月","日");
	$replace = array("-","-","");
	$newsDate = str_replace($search,$replace,$str);
	upinsert($newsId, $newsDate, $pageType, $title, $contents);
	echo '<div class = "id-result">newsID:'.$newsId.'</div><br>';
	echo '<div class = "title-result">タイトル:「'.$title."」のDB格納が完了しました。<br><br>";
}

// JSONデータを作って変数に格納したらgs.jsonってファイルにUTF8で保存してscpでWEBに同期します。
$json = build_json();
$filename = 'gs.json';
$json_utf8 = mb_convert_encoding($json, 'UTF-8');
// print($json);
file_put_contents($filename, $json_utf8);
scp_json($filename);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>ニュース取得</title>
	<!-- <link rel="stylesheet" href="./css/reset.css"> -->
    <link rel="stylesheet" href="./css/style.css"> 

</head>
<body>
<div class = "">

</div>

<br>
<br>
<a href="menu.html">メニューに戻る</a>
</body>
</html>
