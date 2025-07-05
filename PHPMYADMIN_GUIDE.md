# phpMyAdminでのデータベース設定 - 詳細ガイド

## 🎯 このガイドについて

このガイドでは、XAMPPのphpMyAdminを使用して、高校生・大学生マッチングアプリのデータベースを作成する手順を、初心者の方にも分かりやすく説明します。

## 📋 前提条件

- XAMPPがインストールされている
- Apache、MySQLが起動している
- ブラウザでphpMyAdminにアクセスできる

## 🔧 Step-by-Step設定手順

### Step 1: phpMyAdminにアクセス

1. **XAMPPコントロールパネルを開く**
   - Apache: Start（緑色の「Running」状態）
   - MySQL: Start（緑色の「Running」状態）

2. **ブラウザでphpMyAdminにアクセス**
   ```
   http://localhost/phpmyadmin/
   ```

3. **phpMyAdminの画面が表示される**
   - 左側にサイドバー（データベース一覧）
   - 右側にメイン画面

### Step 2: データベースの作成

1. **「新規作成」をクリック**
   - 左サイドバーの上部にある「新規作成」リンク

2. **データベース情報を入力**
   ```
   データベース名: student_review_app
   照合順序: utf8mb4_unicode_ci
   ```

3. **「作成」ボタンをクリック**
   - 成功すると左サイドバーに新しいデータベースが表示される

### Step 3: テーブルの作成

1. **作成したデータベースを選択**
   - 左サイドバーの「student_review_app」をクリック

2. **「SQL」タブをクリック**
   - 画面上部のタブメニューから「SQL」を選択

3. **テーブル作成SQLを入力**
   以下のSQLをコピー&ペースト：

```sql
-- 大学生マスターテーブル
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

-- レビューテーブル
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
```

4. **「実行」ボタンをクリック**
   - 画面右下の「実行」ボタン
   - 成功すると「クエリは正常に実行されました」と表示

### Step 4: サンプルデータの投入

1. **再度「SQL」タブをクリック**

2. **サンプルデータ投入SQLを入力**
   以下のSQLをコピー&ペースト：

```sql
-- 大学生サンプルデータ
INSERT INTO students (name, university, department, grade) VALUES
('山田太郎', '東京大学', '工学部情報理工学科', 3),
('佐藤花子', '早稲田大学', '政治経済学部経済学科', 2),
('田中一郎', '慶應義塾大学', '理工学部情報工学科', 4),
('鈴木美咲', '明治大学', '商学部マーケティング学科', 2),
('高橋健太', '日本大学', '文理学部心理学科', 3),
('渡辺優子', '立教大学', '社会学部現代文化学科', 1),
('中村亮介', '法政大学', 'デザイン工学部建築学科', 4),
('小林麻衣', '青山学院大学', '国際政治経済学部', 2),
('加藤翔太', '上智大学', '理工学部機械工学科', 3),
('山本あいり', '東京理科大学', '薬学部薬学科', 5);

-- レビューサンプルデータ
INSERT INTO reviews (student_id, reviewer_nickname, reviewer_school, reviewer_grade, friendliness, helpfulness, excitement, punctuality, comment, review_date) VALUES
(1, 'ゆうき', '渋谷高校', 3, 5, 5, 4, 5, 'とても親しみやすく、進路について丁寧に教えてくれました！工学部の実際の授業内容や就職先について具体的に聞けて良かったです。', '2025-06-15'),
(1, NULL, '新宿高校', 2, 4, 5, 5, 4, 'プログラミングの勉強方法を詳しく教えてもらいました。東大の雰囲気も教えてもらえて、モチベーションが上がりました！', '2025-06-10'),
(2, 'みなみ', '品川女子学院', 2, 5, 5, 5, 5, '同じ女子として、大学生活のリアルな話が聞けて参考になりました！経済学の面白さを教えてもらえて、文系選択に自信が持てました。', '2025-06-12'),
(3, 'りょうた', '麻布高校', 3, 3, 4, 3, 4, '慶應の理工学部について詳しく教えてもらいました。ちょっと緊張気味でしたが、質問には答えてくれました。', '2025-06-14'),
(4, 'なつき', '女子学院', 2, 5, 4, 5, 5, '明るくて話しやすい先輩でした！マーケティングの勉強内容について、具体例を交えて説明してくれて分かりやすかったです。', '2025-06-11');
```

3. **「実行」ボタンをクリック**
   - 成功すると「クエリは正常に実行されました」と表示

### Step 5: データの確認

1. **studentsテーブルの確認**
   - 左サイドバーの「students」をクリック
   - 「表示」タブで10件の大学生データを確認

2. **reviewsテーブルの確認**
   - 左サイドバーの「reviews」をクリック
   - 「表示」タブで5件のレビューデータを確認

## ✅ 確認チェックリスト

作業完了後、以下を確認してください：

- [ ] データベース「student_review_app」が作成されている
- [ ] studentsテーブルが作成されている（6カラム）
- [ ] reviewsテーブルが作成されている（13カラム）
- [ ] studentsテーブルに10件のデータが入っている
- [ ] reviewsテーブルに5件のデータが入っている
- [ ] 外部キー制約が正しく設定されている

## 🚨 トラブルシューティング

### よくあるエラーと解決方法

**1. 「Access denied」エラー**
- 解決法: XAMPPでMySQLが起動しているか確認

**2. 「Database doesn't exist」エラー**
- 解決法: データベース名を正しく入力したか確認

**3. 「Foreign key constraint fails」エラー**
- 解決法: studentsテーブルを先に作成してからreviewsテーブルを作成

**4. 「Duplicate entry」エラー**
- 解決法: 既存のデータを削除してから再実行

**5. 文字化けが発生する**
- 解決法: 文字コードを「utf8mb4_unicode_ci」に設定

## 🔧 メンテナンス

### データのバックアップ

1. **データベースを選択**
2. **「エクスポート」タブをクリック**
3. **「実行」ボタンでSQLファイルをダウンロード**

### データのリセット

1. **テーブルを削除**
   ```sql
   DROP TABLE IF EXISTS reviews;
   DROP TABLE IF EXISTS students;
   ```

2. **再度テーブル作成・データ投入を実行**

## 🎯 次のステップ

データベースの設定が完了したら：

1. **アプリケーションの動作確認**
   ```
   http://localhost/gs_kadai/kadai07_db1/index.php
   ```

2. **データベース接続テスト**
   ```
   http://localhost/gs_kadai/kadai07_db1/db_test.php
   ```

3. **各機能のテスト**
   - レビュー一覧表示
   - レビュー投稿
   - データテーブル表示
   - レビューの編集・削除

## 📞 サポート

このガイドで解決できない問題がある場合：

1. **エラーメッセージをメモ**
2. **どの手順で問題が発生したかを確認**
3. **XAMPPのエラーログを確認**
4. **データベースの状態を確認**

---

*作成日: 2025年7月5日*  
*高校生・大学生マッチングアプリ - データベース設定ガイド*
