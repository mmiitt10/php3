<?php

function db_conn() {
    try {
        $db_name = 'homework'; // データベース名
        $db_id   = 'root'; // データベースのユーザー名
        $db_pw   = ''; // データベースのパスワード
        $db_host = 'localhost'; // データベースのホスト名

        return new PDO('mysql:dbname=' . $db_name . ';charset=utf8;host=' . $db_host, $db_id, $db_pw);
    } catch (PDOException $e) {
        exit('DB Connection Error:' . $e->getMessage());
    }
}

function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function redirect($url) {
    header("Location: " . $url);
    exit;
}

?>
