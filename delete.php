<!-- //PHP:コード記述/修正の流れ
//1. insert.phpの処理をマルっとコピー。
//2. $id = $_POST["id"]を追加
//3. SQL修正
//   "UPDATE テーブル名 SET 変更したいカラムを並べる WHERE 条件"
//   bindValueにも「id」の項目を追加
//4. header関数"Location"を「select.php」に変更 -->

<?php

//1. POSTデータ取得
$id=$_GET["id"];

//2. DB接続します
//*** function化する！  *****************
require_once("config.php");
$pdo=db_conn();

//３．データ登録SQL作成：UPDATEするための変数を記載
$stmt = $pdo->prepare("DELETE from php3 Where id=:id;");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute(); //実行

//４．データ登録処理後
if ($status === false) {
    //*** function化する！******\
    $error = $stmt->errorInfo();
    exit('SQLError:' . print_r($error, true));
} else {
    //*** function化する！*****************
    header('Location: select.php');
    exit();
}