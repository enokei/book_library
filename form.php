<!DOCTYPE html>

<head>
 <meta charset="UTF-8">
 <title>Book Search</title>
 <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
 <script src="bootstrap/js/bootstrap.min.js"></script>
</head>

<body>
<h1>登録フォーム</h1>

<?php

//外部データベース設定
if( ! $conn = mysql_connect( 'localhost', 'root', '') ){
die( 'Error' );
}
mysql_select_db( '', $conn );
mysql_query('SET NAMES utf8');

//----------------
//開発者の情報
//----------------
define("ACCESS_KEY_ID", '');
define("SECRET_ACCESS_KEY", '');

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

$result = $amazon->ItemSearch('Books',$options);

function displayOne($item){
   $attributes = $item['ItemAttributes'];
   var_dump($attributes);
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
	echo "<form action='' method='post'>";
	echo "<label for='author'>著者</label>";
	echo "<input type='text' name='author' value=${author}/>";
    echo "<label for='price'>価格</label>";
	echo "<input type='text' name='price' value=$formattedPrice/>";
	echo "<label for='ISBN-10'>ISBN</label>";
	echo "<input type='text' name='ISBN-10' value=$asin/>";
	echo "<label for='place'>保管場所</label>";
    echo "<select id='place' name='place'>";
    echo "<option value='seven'>7F ARC</option>";
    echo "<option value='eight'>8F 業務室</option>";
    echo "</select>";
	echo "<input type='submit'>登録する";	
	echo "</form>"; 
	echo "</pre>";

}

?>

</body>
</html>