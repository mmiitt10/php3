<?php

require_once("config.php");
$pdo = db_conn();


/**
 * [ここでやりたいこと]
 * 1. クエリパラメータの確認 = GETで取得している内容を確認する
 * 2. select.phpのPHP<?php ?>の中身をコピー、貼り付け
 * 3. SQL部分にwhereを追加
 * 4. データ取得の箇所を修正。
 */

    $id=$_GET['id'];



    //２．データ登録SQL作成
    //idが$idで定義されているものを持ってくる 
    // そのうえで、入力された情報、
    $stmt = $pdo->prepare('SELECT * FROM php3 where id=:id;');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $status = $stmt->execute();

    //３．データ表示
    $view = '';
    if ($status === false) {
        $error = $stmt->errorInfo();
        exit('SQLError:' . print_r($error, true));
    } else {
        $result=$stmt->fetch();
    }
    $save_error = ''; // 変数を初期化

?>
<!--
２．HTML
以下にindex.phpのHTMLをまるっと貼り付ける！
(入力項目は「登録/更新」はほぼ同じになるから)
※form要素 input type="hidden" name="id" を１項目追加（非表示項目）
※form要素 action="update.php"に変更
※input要素 value="ここに変数埋め込み"
-->

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($volumeInfo) ? htmlspecialchars($volumeInfo->title) : '書籍詳細'; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php if ($result): ?>
        <h1><?php echo htmlspecialchars($result['title']); ?></h1>
        <!-- 画像の表示や他のデータの表示 -->
        <p>著者: <?php echo htmlspecialchars($result['author']); ?></p>
        <p>発売日: <?php echo htmlspecialchars($result['publishDate']); ?></p>
        <p>カテゴリ: <?php echo htmlspecialchars($result['category']); ?></p>
        <p>説明: <?php echo htmlspecialchars($result['description']); ?></p>
        <!-- 他のデータを表示するコード -->

        <!-- フォーム -->
        <form action="update.php" method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <input type="hidden" name="author" value="<?php echo htmlspecialchars($result['author']); ?>">
            <input type="hidden" name="title" value="<?php echo htmlspecialchars($result['title']); ?>">
            <input type="hidden" name="category" value="<?php echo htmlspecialchars($result['category']); ?>">
            <input type="hidden" name="description" value="<?php echo htmlspecialchars($result['description']); ?>">
            <input type="hidden" name="publishDate" value="<?php echo htmlspecialchars($result['publishDate']); ?>">

            <textarea name="memo" placeholder="コメントを入力"><?php echo htmlspecialchars($result['memo']); ?></textarea><br>
            <button type="submit">更新</button>
        </form>


        <!-- エラーメッセージの表示 -->
        <?php if (isset($save_error) && $save_error): ?>
            <p><?php echo htmlspecialchars($save_error); ?></p>
        <?php endif; ?>
    <?php else: ?>
        <p>書籍の情報を取得できませんでした。</p>
    <?php endif; ?>
</body>
</html>