-- 高校生・大学生マッチングアプリ レビュー機能用データベース
-- 作成日: 2025年6月21日

-- データベースの作成
CREATE DATABASE IF NOT EXISTS student_review_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- データベースを使用
USE student_review_app;

-- 大学生テーブルの作成
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT '大学生ID',
    name VARCHAR(100) NOT NULL COMMENT '氏名',
    university VARCHAR(100) NOT NULL COMMENT '大学名',
    department VARCHAR(100) NOT NULL COMMENT '学部・学科',
    grade INT NOT NULL COMMENT '学年（1-4, 5=院生）',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '登録日時',
    INDEX idx_university (university),
    INDEX idx_department (department)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='大学生マスターテーブル';

-- レビューテーブルの作成
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'レビューID',
    student_id INT NOT NULL COMMENT '大学生ID',
    reviewer_nickname VARCHAR(50) DEFAULT NULL COMMENT '投稿者ニックネーム',
    reviewer_school VARCHAR(100) NOT NULL COMMENT '投稿者高校名',
    reviewer_grade INT NOT NULL COMMENT '投稿者学年（1-3）',
    friendliness INT NOT NULL COMMENT '話しやすさ（1-5）',
    helpfulness INT NOT NULL COMMENT '参考になった度（1-5）',
    excitement INT NOT NULL COMMENT 'ワクワク度（1-5）',
    punctuality INT NOT NULL COMMENT '時間の正確性（1-5）',
    comment TEXT DEFAULT NULL COMMENT 'コメント',
    review_date DATE NOT NULL COMMENT 'レビュー日',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '投稿日時',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日時',
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    INDEX idx_student_id (student_id),
    INDEX idx_review_date (review_date),
    INDEX idx_created_at (created_at),
    CHECK (friendliness BETWEEN 1 AND 5),
    CHECK (helpfulness BETWEEN 1 AND 5),
    CHECK (excitement BETWEEN 1 AND 5),
    CHECK (punctuality BETWEEN 1 AND 5),
    CHECK (reviewer_grade BETWEEN 1 AND 3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='レビューテーブル';
