<!-- //PHP:コード記述/修正の流れ
//1. insert.phpの処理をマルっとコピー。
//2. $id = $_POST["id"]を追加
//3. SQL修正
//   "UPDATE テーブル名 SET 変更したいカラムを並べる WHERE 条件"
//   bindValueにも「id」の項目を追加
//4. header関数"Location"を「select.php」に変更 -->

<?php

//1. POSTデータ取得
$author = isset($_POST['author']) ? $_POST['author'] : '';
$title = isset($_POST['title']) ? $_POST['title'] : '';
$category = isset($_POST['category']) ? $_POST['category'] : '';
$description = isset($_POST['description']) ? $_POST['description'] : '';
$publishDate = isset($_POST['publishDate']) ? $_POST['publishDate'] : '';
$memo = isset($_POST['memo']) ? $_POST['memo'] : '';
$id = isset($_POST['id']) ? $_POST['id'] : '';


//2. DB接続します
//*** function化する！  *****************
require_once("config.php");
$pdo=db_conn();

//３．データ登録SQL作成：UPDATEするための変数を記載
$stmt = $pdo->prepare('UPDATE php3 SET memo=:memo where id=:id');

// 数値の場合 PDO::PARAM_INT
// 文字の場合 PDO::PARAM_STR
$stmt->bindValue(':memo', $memo, PDO::PARAM_STR);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute(); 
//実行

//４．データ登録処理後
if ($status === false) {
    //*** function化する！******\
    $error = $stmt->errorInfo();
    exit('SQLError:' . print_r($error, true));
} else {
    //*** function化する！*****************
    header('Location: index.php');
    exit();
}