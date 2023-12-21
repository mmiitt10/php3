
<?php
//1.  DB接続します

// 複数のページで関数を使用する場合に必要となるコード
require_once("config.php");
$pdo = db_conn();



//２．データ取得SQL作成
// フォームに登録された内容をDBに格納する場合はSQL対策が必要だが、DBから引っ張り出す際は不要
$stmt = $pdo->prepare("SELECT * From php3");
$status = $stmt->execute();

//３．データ表示
$view="";
if ($status==false) {
    //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);
}else{
  //Selectデータの数だけ自動でループしてくれる
  //FETCH_ASSOC=http://php.net/manual/ja/pdostatement.fetch.php
  while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $view .= '<p>';
    $view .= '<a href="detail.php?id=' . $result['id'] . '">';
    $view .= $result['author'] . '：' . $result['title'];
    $view .= '</a>';

    $view .= '<a href="delete.php?id=' . $result['id'] . '" onclick="return confirm(\'本当に削除してよろしいですか？\');">';
    $view .= '  : [削除]';
    $view .= '</a>';
    
    $view .= '</p>';
    }
}
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($volumeInfo->title); ?>の詳細</title>
    <a href="index.php">データ登録</a>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div>
        <div class="container jumbotron">
            <a href="book_detail.php"></a>
            <?= $view ?>
        </div>
    </div>
    <!-- Main[End] -->
</body>
</html>
