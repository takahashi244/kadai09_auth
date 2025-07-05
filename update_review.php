<?php
// POSTデータ取得
$id = $_POST["id"];
$student_id = $_POST["student_id"];
$reviewer_nickname = $_POST["reviewer_nickname"] ?? null;
$reviewer_school = $_POST["reviewer_school"];
$reviewer_grade = $_POST["reviewer_grade"];
$friendliness = $_POST["friendliness"];
$helpfulness = $_POST["helpfulness"];
$excitement = $_POST["excitement"];
$punctuality = $_POST["punctuality"];
$comment = $_POST["comment"] ?? null;
$review_date = $_POST["review_date"];

// バリデーション
$errors = [];

// 必須項目チェック
if (empty($id) || !is_numeric($id)) {
    $errors[] = '不正なレビューIDです。';
}
if (empty($student_id) || !is_numeric($student_id)) {
    $errors[] = '不正な大学生IDです。';
}
if (empty($reviewer_school)) {
    $errors[] = '高校名は必須です。';
}
if (empty($reviewer_grade) || !in_array($reviewer_grade, ['1', '2', '3'])) {
    $errors[] = '学年を正しく選択してください。';
}
if (empty($review_date)) {
    $errors[] = '面談日は必須です。';
}

// 評価値チェック
$ratings = [$friendliness, $helpfulness, $excitement, $punctuality];
foreach ($ratings as $rating) {
    if (empty($rating) || !in_array($rating, ['1', '2', '3', '4', '5'])) {
        $errors[] = '評価は1〜5の値で入力してください。';
        break;
    }
}

// 文字数制限チェック
if (strlen($reviewer_nickname) > 50) {
    $errors[] = 'ニックネームは50文字以内で入力してください。';
}
if (strlen($reviewer_school) > 100) {
    $errors[] = '高校名は100文字以内で入力してください。';
}

// エラーがある場合は編集画面に戻る
if (!empty($errors)) {
    $error_message = implode('<br>', $errors);
    echo "<script>
        alert('エラーが発生しました:\\n{$error_message}');
        history.back();
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
        die('指定されたレビューが見つかりません。');
    }

    // データ更新SQL作成
    $sql = "UPDATE reviews SET 
                student_id = :student_id,
                reviewer_nickname = :reviewer_nickname,
                reviewer_school = :reviewer_school,
                reviewer_grade = :reviewer_grade,
                friendliness = :friendliness,
                helpfulness = :helpfulness,
                excitement = :excitement,
                punctuality = :punctuality,
                comment = :comment,
                review_date = :review_date
            WHERE id = :id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->bindValue(':reviewer_nickname', $reviewer_nickname, PDO::PARAM_STR);
    $stmt->bindValue(':reviewer_school', $reviewer_school, PDO::PARAM_STR);
    $stmt->bindValue(':reviewer_grade', $reviewer_grade, PDO::PARAM_INT);
    $stmt->bindValue(':friendliness', $friendliness, PDO::PARAM_INT);
    $stmt->bindValue(':helpfulness', $helpfulness, PDO::PARAM_INT);
    $stmt->bindValue(':excitement', $excitement, PDO::PARAM_INT);
    $stmt->bindValue(':punctuality', $punctuality, PDO::PARAM_INT);
    $stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindValue(':review_date', $review_date, PDO::PARAM_STR);
    
    $status = $stmt->execute();

    // 処理結果
    if ($status == false) {
        $error = $stmt->errorInfo();
        error_log("レビュー更新エラー: " . $error[2]);
        echo "<script>
            alert('レビューの更新中にエラーが発生しました。');
            history.back();
        </script>";
    } else {
        echo "<script>
            alert('レビューを更新しました！');
            location.href = 'index.php';
        </script>";
    }

} catch (Exception $e) {
    error_log("レビュー更新エラー: " . $e->getMessage());
    echo "<script>
        alert('レビューの更新中にエラーが発生しました。');
        history.back();
    </script>";
}
?>
