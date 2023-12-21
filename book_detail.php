<?php
// データベース設定ファイルを読み込む
require_once ('config.php');
$pdo = db_conn();

// 書籍IDを取得
$book_id = isset($_GET['id']) ? $_GET['id'] : '';
$book_detail = null;
$save_error = '';

if ($book_id) {
    // Google Books APIのURL
    $url = "https://www.googleapis.com/books/v1/volumes/" . urlencode($book_id);

    // APIからのレスポンスを取得
    $response = file_get_contents($url);

    // レスポンスをデコード
    $book_detail = json_decode($response);
}

if (!$book_detail) {
    echo "書籍の情報を取得できませんでした。";
    exit;
}

// ここから先は $book_detail を使用して書籍情報を表示
$volumeInfo = $book_detail->volumeInfo;
// 以下、HTMLコードを続ける
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($volumeInfo) ? htmlspecialchars($volumeInfo->title) : '書籍詳細'; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php if (isset($volumeInfo)): ?>
        <h1><?php echo htmlspecialchars($volumeInfo->title); ?></h1>
        <?php if (isset($volumeInfo->imageLinks) && isset($volumeInfo->imageLinks->thumbnail)): ?>
            <img src="<?php echo htmlspecialchars($volumeInfo->imageLinks->thumbnail); ?>" alt="<?php echo htmlspecialchars($volumeInfo->title); ?>">
        <?php else: ?>
            <p>画像が利用できません</p>
        <?php endif; ?>
        <p>著者: <?php echo isset($volumeInfo->authors) ? implode(', ', $volumeInfo->authors) : '著者不明'; ?></p>
        <p>発売日: <?php echo isset($volumeInfo->publishedDate) ? htmlspecialchars($volumeInfo->publishedDate) : '日付不明'; ?></p>
        <p>カテゴリ: <?php echo isset($volumeInfo->categories) ? implode(', ', $volumeInfo->categories) : 'カテゴリなし'; ?></p>
        <p>説明: <?php echo isset($volumeInfo->description) ? htmlspecialchars($volumeInfo->description) : '説明なし'; ?></p>

        <!-- フォーム -->
        <form action="insert.php" method="post">
            <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($book_id); ?>">
            <textarea name="memo" placeholder="コメントを入力"></textarea><br>
            <button type="submit">お気に入りに追加</button>
        </form>

        <?php if ($save_error): ?>
            <p><?php echo htmlspecialchars($save_error); ?></p>
        <?php endif; ?>
    <?php else: ?>
        <p>書籍の情報を取得できませんでした。</p>
    <?php endif; ?>
</body>
</html>
