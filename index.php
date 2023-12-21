<?php
// 検索結果を格納するための変数
$books = array();
$search_error = '';
$total_items = 0;
$items_per_page = 10;
$current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$start_index = ($current_page - 1) * $items_per_page;

if (isset($_GET['search'])) {
    $search_query = trim($_GET['search']);
    
    if (!empty($search_query)) {
        // Google Books APIのURL
        $url = "https://www.googleapis.com/books/v1/volumes?q=" . urlencode($search_query) . "&startIndex=" . $start_index . "&maxResults=" . $items_per_page;

        // APIからのレスポンスを取得
        $response = file_get_contents($url);

        // レスポンスをデコード
        $data = json_decode($response);

        if ($data && isset($data->items)) {
            $books = $data->items;
            $total_items = $data->totalItems;
        } else {
            $search_error = '検索結果が見つかりませんでした。';
        }
    } else {
        $search_error = '検索クエリを入力してください。';
    }
}

// ページネーションの計算
$total_pages = ceil($total_items / $items_per_page);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>書籍検索</title>
    <a href="select.php">データ一覧</a>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>
    <h1>書籍検索</h1>

    <!-- 検索フォーム -->
    <form method="get">
        <input type="text" name="search" placeholder="書籍名または著者名で検索" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit">検索</button>
    </form>

    <?php if ($search_error): ?>
        <p><?php echo htmlspecialchars($search_error); ?></p>
    <?php else: ?>
        <p>全<?php echo $total_items; ?>件中 <?php echo (($current_page - 1) * $items_per_page + 1); ?> - <?php echo min($current_page * $items_per_page, $total_items); ?>件を表示中</p>
    <?php endif; ?>

    <!-- 検索結果の表示 -->
    <div id="books">
        <?php foreach ($books as $book): ?>
            <div class="book">
                <?php
                    // サムネイル画像
                    $thumbnail = isset($book->volumeInfo->imageLinks) && isset($book->volumeInfo->imageLinks->thumbnail) ? $book->volumeInfo->imageLinks->thumbnail : 'no_image.png';
                ?>
                <img src="<?php echo htmlspecialchars($thumbnail); ?>" alt="<?php echo htmlspecialchars($book->volumeInfo->title); ?>">
                <h2><a href="book_detail.php?id=<?php echo urlencode($book->id); ?>"><?php echo htmlspecialchars($book->volumeInfo->title); ?></a></h2>
                <p>著者: <?php echo isset($book->volumeInfo->authors) ? implode(', ', $book->volumeInfo->authors) : '著者不明'; ?></p>
                <p>カテゴリ: <?php echo isset($book->volumeInfo->categories) ? implode(', ', $book->volumeInfo->categories) : 'カテゴリなし'; ?></p>
                <p>説明: <?php echo isset($book->volumeInfo->description) ? htmlspecialchars($book->volumeInfo->description) : '説明なし'; ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- ページネーション -->
    <div id="pagination">
        <?php if ($total_pages > 1): ?>
            <!-- 最初のページへ -->
            <a href="?search=<?php echo urlencode($search_query); ?>&page=1"><<</a>

            <!-- 前のページへ -->
            <?php if ($current_page > 1): ?>
                <a href="?search=<?php echo urlencode($search_query); ?>&page=<?php echo ($current_page - 1); ?>"><</a>
            <?php endif; ?>

            <?php
            // ページネーションの表示範囲を計算
            $start_page = max(1, $current_page - 5);
            $end_page = min($total_pages, $current_page + 4);
            for ($i = $start_page; $i <= $end_page; $i++): ?>
                <a href="?search=<?php echo urlencode($search_query); ?>&page=<?php echo $i; ?>"<?php if ($i == $current_page) echo ' class="current-page"'; ?>><?php echo $i; ?></a>
            <?php endfor; ?>

            <!-- 次のページへ -->
            <?php if ($current_page < $total_pages): ?>
                <a href="?search=<?php echo urlencode($search_query); ?>&page=<?php echo ($current_page + 1); ?>">></a>
            <?php endif; ?>

            <!-- 最後のページへ -->
            <a href="?search=<?php echo urlencode($search_query); ?>&page=<?php echo $total_pages; ?>">>></a>
        <?php endif; ?>
    </div>
</body>
</html>
