# 高校生・大学生マッチング - WEB面談レビューシステム

## 概要

高校生が大学生とのWEB面談後にレビューを投稿・閲覧できるシステムです。大学選びや進路相談の参考にすることができます。

## 機能

- **レビュー一覧表示**: 大学生への面談レビューを一覧表示
- **検索・絞り込み**: 大学名、学部、評価、投稿日で絞り込み検索
- **レビュー投稿**: 面談後のレビューを投稿（話しやすさ、参考度、ワクワク度、時間正確性の4項目評価）
- **データ一覧**: レビューデータをテーブル形式で確認、統計情報も表示
- **環境別設定**: ローカル環境と本番環境で自動的にDB設定を切り替え

## 技術仕様

- **言語**: PHP 8.x, HTML5, CSS3, JavaScript
- **データベース**: MySQL 8.x
- **フレームワーク**: なし（Pure PHP）
- **サーバー**: Apache（XAMPP/さくらサーバー対応）

## ファイル構成

```
kadai07_db1/
├── index.php                    # メインページ（レビュー一覧・検索）
├── post_review.php             # レビュー投稿ページ
├── view_reviews_table.php      # データ一覧ページ
├── config/
│   ├── database.php            # DB接続設定（環境判定機能付き）
│   ├── env.example.php         # 本番環境設定のテンプレート
│   └── env.php                 # 本番環境設定（GitHubには含まれません）
├── css/
│   └── style.css              # スタイルシート
├── database/
│   ├── create_database.sql    # テーブル作成SQL
│   └── insert_dummy_data.sql  # 初期データ投入SQL
├── .gitignore                 # Git除外設定
└── README_DATABASE.md         # このファイル
```

## 環境別データベース設定

### 🔧 ローカル環境（XAMPP）
- 自動的にlocalhost設定を使用
- データベース名: `student_review_app`
- ユーザー: `root`
- パスワード: なし

### 🌐 本番環境（さくらサーバー）
- `config/env.php` から設定を読み込み
- 環境判定は `$_SERVER["SERVER_NAME"]` で自動実行

## セットアップ手順

### ローカル環境（XAMPP）

1. **XAMPPの起動**
   ```
   Apache, MySQL を起動
   ```

2. **データベース作成**
   - phpMyAdminで `student_review_app` データベースを作成
   - `database/create_database.sql` を実行
   - `database/insert_dummy_data.sql` を実行

3. **アクセス確認**
   ```
   http://localhost/gs_kadai/kadai07_db1/index.php
   ```

### 本番環境（さくらサーバー）

1. **ファイルアップロード**
   - GitHubからプロジェクトをダウンロード
   - FTPで `/home/ドメイン名/www/kadai07_db1/` にアップロード

2. **環境設定ファイル作成**
   ```bash
   # config/env.example.php をコピーして config/env.php を作成
   # 実際のさくらサーバー情報を入力
   ```

3. **データベース設定**
   - さくらのコントロールパネルでデータベース作成
   - phpMyAdminでテーブル作成・データ投入

4. **アクセス確認**
   ```
   https://your-domain.sakura.ne.jp/kadai07_db1/index.php
   ```

## データベース設計

### studentsテーブル（大学生マスター）
| カラム名 | 型 | 説明 |
|----------|----|----|
| id | INT | 大学生ID（主キー） |
| name | VARCHAR(100) | 氏名 |
| university | VARCHAR(100) | 大学名 |
| department | VARCHAR(100) | 学部・学科 |
| grade | INT | 学年（1-4, 5=院生） |
| created_at | TIMESTAMP | 登録日時 |

### reviewsテーブル（レビュー）
| カラム名 | 型 | 説明 |
|----------|----|----|
| id | INT | レビューID（主キー） |
| student_id | INT | 大学生ID（外部キー） |
| reviewer_nickname | VARCHAR(50) | 投稿者ニックネーム |
| reviewer_school | VARCHAR(100) | 投稿者高校名 |
| reviewer_grade | INT | 投稿者学年（1-3） |
| friendliness | INT | 話しやすさ（1-5） |
| helpfulness | INT | 参考になった度（1-5） |
| excitement | INT | ワクワク度（1-5） |
| punctuality | INT | 時間の正確性（1-5） |
| comment | TEXT | コメント |
| review_date | DATE | レビュー日 |
| created_at | TIMESTAMP | 投稿日時 |

## セキュリティ対策

### 🔒 秘匿情報保護
- `config/env.php` を `.gitignore` で除外
- 本番DBパスワードはGitHubに含まれません

### 🛡️ SQLインジェクション対策
- プリペアドステートメント使用
- 入力値の型変換・検証

### 🚨 XSS対策
- `htmlspecialchars()` 関数で出力エスケープ
- `h()` 関数でXSS対応

## 使用方法

### レビュー閲覧
1. `index.php` でレビュー一覧を確認
2. 検索条件で絞り込み可能
3. 大学名、学部、評価、投稿日での絞り込み

### レビュー投稿
1. `post_review.php` でレビューを投稿
2. 4項目の評価（1-5点）とコメントを入力
3. 投稿者情報（ニックネーム、高校名、学年）を入力

### データ確認
1. `view_reviews_table.php` でデータを一覧表示
2. 統計情報（平均評価など）も確認可能

## デプロイ手順

1. **GitHubへpush**（秘匿情報は自動除外）
2. **さくらサーバーにファイルアップロード**
3. **env.phpを手動アップロード**
4. **データベース・テーブル作成**
5. **動作確認**

## トラブルシューティング

### よくあるエラー

**「env.phpが見つかりません」**
- 本番環境で `config/env.php` が手動アップロードされているか確認

**「データベース接続エラー」**
- `env.php` の設定値を確認
- さくらサーバーのDB情報と一致しているか確認

**「テーブルが存在しません」**
- phpMyAdminでテーブルが作成されているか確認
- `create_database.sql` の実行を確認

## 更新履歴

- **2025-06-28**: 環境別DB設定機能追加、セキュリティ強化
- **2025-06-21**: 初回リリース
