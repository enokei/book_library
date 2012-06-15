<!DOCTYPE html>

<head>
 <meta charset="UTF-8">
 <title>Book Search</title>
 <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
 <script src="bootstrap/js/bootstrap.min.js"></script>
</head>

<body onLoad="document.search.words.focus();">
<h1>UEI Book Search</h1>

<?php

//----------------
//開発者の情報
//----------------
define("ACCESS_KEY_ID", '');
define("SECRET_ACCESS_KEY", '');

//PHP４の書式までは許可
error_reporting(E_ALL ^ E_DEPRECATED);

//PHPの実行時間制限をなくす
set_time_limit(0);

//外部への通信時間
ini_set('default_socket_timeout',60*5);

//---------------------
//パラメータ•フォーム処理
//---------------------

$words ='';  //デフォルトキーワード
if(isset($_REQUEST['words'])){
   $words=trim($_REQUEST['words']);
}

//フォーム中に表示するためにエスケープする
$words_for_form=htmlspecialchars($words,ENT_QUOTES);

//フォームの表示
echo <<<HTML_FORM_END
<form name="search" action="" method="get">
<label>キーワード or バーコードで検索:</label>
<input type="text" name="words" size="50" value="$words_for_form" required="Yes">
<br/>
<input type="submit" value="Search">
</form>

HTML_FORM_END;
if(empty($words)){
    exit();
}

//-----------------
//Webサービス呼び出し
//-----------------
require_once 'Cache.php';
require_once 'Pager/Pager.php';
require_once 'Services/Amazon.php';

//サービスオブジェクトの生成
$amazon =new Services_Amazon(ACCESS_KEY_ID, SECRET_ACCESS_KEY, "uei-22");

$result =$amazon->setLocale('JP');
if(PEAR::isError($result)){
    echo "対応していない地域です";
    exit ();
}

//キャッシュの設定
$result=$amazon->setCache(
    'file',  //キャッシュはファイルで行う
    array('cache_dir'=>'cached/')   //キャッシュの保存先
);

if(PEAR::isError($result)){
  echo htmlspecialchars($result->message,ENT_QUOTES);
  echo "キャッシュの設定に失敗しました";
  exit();
}

//同一のキーワードに関するキャッシュは２４時間
$amazon->setCacheExpire(24*60*60);

//検索ワードと取得情報の細かさの指定
$options['Keywords']=$words;
$options['ResponseGroup']='Medium';
$options['OfferPage']='1';

//検索の実行
$result = $amazon->ItemSearch('Books',$options);

if (PEAR::isError($result)){
  echo htmlspecialchars($result->message,ENT_QUOTES);
  echo "検索に失敗しました";
  exit();
}

//echo '<pre>';var_dump($result); echo'</pre>';

//------------------
//結果表示
//------------------

//検索キーワードを表示向けにエスケープ
$keywords =htmlspecialchars(
   $result['Request']['ItemSearchRequest']['Keywords'],ENT_QUOTES);
$totalResults =(integer)($result['TotalResults']);

echo "<h2>[$keywords]の検索結果</h2>";
echo "<p><dt>検索件数</dt><dd>$totalResults</dd><br/>";

//取得したアイテムの件数だけ繰り返す
foreach($result['Item'] as $item){
  displayOne($item);
}

//一件分の結果を表示する関数
function displayOne($item){
   $attributes = $item['ItemAttributes'];

    $title=htmlspecialchars($attributes['Title'],ENT_QUOTES);
    if(isset($attributes['Author'])){
      $author=htmlspecialchars(implode(',',$attributes['Author']),ENT_QUOTES);
    }else{
       $author='(著者なし)';
    } 
    $detailUrl=htmlspecialchars($item['DetailPageURL'],ENT_QUOTES);
    $imageUrl=htmlspecialchars($item['MediumImage']['URL'],ENT_QUOTES);
    $formattedPrice=htmlspecialchars($attributes['ListPrice']['FormattedPrice'],ENT_QUOTES);
    $asin=htmlspecialchars($item['ASIN'],ENT_QUOTES);
    $salesRank=(integer)($item['SalesRank']);
        
	//整形して表示
	echo "<pre>";
	echo "<h3>タイトル:${title}</h3>";
	echo "<a href=${detailUrl}>";
	echo "<img src=${imageUrl}>";
	echo "</a>";
	echo "<dl>";
	echo "<dt>著者</dt><dd>${author}</dd>";
	echo "<dt>価格</dt><dd>$formattedPrice</dd>";
	echo "<dt>ISBN-10</dt><dd>$asin</dd>";
	echo "<dt>売り上げランク</dt><dd>${salesRank}</dd>";
	echo "<a href='form.php'>登録する</a>";
	echo "</dl>"; 
	echo "</pre>";
}
?>

</body>
</html>
