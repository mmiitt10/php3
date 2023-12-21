<?php
// データベース設定ファイルを読み込む
require_once ('config.php');
$pdo = db_conn();

// POSTリクエストからデータを取得
$book_id = isset($_POST['book_id']) ? $_POST['book_id'] : '';
$memo = isset($_POST['memo']) ? $_POST['memo'] : '';

// バリデーション
if (!$book_id || !$memo) {
    echo "必要な情報が不足しています。";
    exit;
}

// Google Books APIから書籍情報を取得
$url = "https://www.googleapis.com/books/v1/volumes/" . urlencode($book_id);
$response = file_get_contents($url);
$book_detail = json_decode($response);

if (!$book_detail) {
    echo "書籍の情報を取得できませんでした。";
    exit;
}

// 書籍情報の処理
$volumeInfo = $book_detail->volumeInfo;
$author = isset($volumeInfo->authors) ? implode(', ', $volumeInfo->authors) : '';
$title = $volumeInfo->title;
$category = isset($volumeInfo->categories) ? implode(', ', $volumeInfo->categories) : '';
$description = $volumeInfo->description ?? ''; // descriptionが存在しない場合は空文字列を使用
$publishDate = $volumeInfo->publishedDate ?? ''; // publishDateが存在しない場合は空文字列を使用

// データベースへの挿入
try {
    $stmt = $pdo->prepare("INSERT INTO php3 (author, title, category, description, publishDate, memo) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$author, $title, $category, $description, $publishDate, $memo]);
    echo "データが保存されました。";
} catch (PDOException $e) {
    echo "データベースエラー: " . $e->getMessage();
    exit;
}
redirect("index.php");
?>
