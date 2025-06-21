<?php
/**
 * データベース接続設定
 * 作成日: 2025年6月21日
 */

// データベース接続情報
define('DB_HOST', 'localhost');
define('DB_NAME', 'student_review_app');
define('DB_USER', 'root');
define('DB_PASS', ''); // XAMPPのデフォルトは空文字

// データベース接続関数
function getDBConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    } catch (PDOException $e) {
        die("データベース接続に失敗しました: " . $e->getMessage());
    }
}

// エラーハンドリング用関数
function handleDBError($e) {
    error_log("データベースエラー: " . $e->getMessage());
    die("システムエラーが発生しました。管理者にお問い合わせください。");
}
?>
