<?php
// GETでレビューIDを受け取る
$id = $_GET["id"] ?? null;

// バリデーション
if (empty($id) || !is_numeric($id)) {
    echo "<script>
        alert('不正なアクセスです。');
        location.href = 'index.php';
    </script>";
    exit;
}

// データベース接続
require_once 'config/database.php';
$pdo = getDBConnection();

try {
    // レビューの存在確認
    $check_sql = "SELECT COUNT(*) FROM reviews WHERE id = :id";
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $check_stmt->execute();
    
    if ($check_stmt->fetchColumn() == 0) {
        echo "<script>
            alert('指定されたレビューが見つかりません。');
            location.href = 'index.php';
        </script>";
        exit;
    }

    // データ削除SQL実行
    $delete_sql = "DELETE FROM reviews WHERE id = :id";
    $delete_stmt = $pdo->prepare($delete_sql);
    $delete_stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $status = $delete_stmt->execute();

    // 処理結果
    if ($status == false) {
        $error = $delete_stmt->errorInfo();
        error_log("レビュー削除エラー: " . $error[2]);
        echo "<script>
            alert('レビューの削除中にエラーが発生しました。');
            location.href = 'index.php';
        </script>";
    } else {
        echo "<script>
            alert('レビューを削除しました。');
            location.href = 'index.php';
        </script>";
    }

} catch (Exception $e) {
    error_log("レビュー削除エラー: " . $e->getMessage());
    echo "<script>
        alert('レビューの削除中にエラーが発生しました。');
        location.href = 'index.php';
    </script>";
}
?>
