# 高校生・大学生マッチング - WEB面談レビューシステム

## 環境別データベース設定

このプロジェクトは、ローカル環境（XAMPP）と本番環境（さくらサーバー）で自動的にデータベース設定を切り替えます。

### ファイル構成

```
config/
├── database.php          # メインの接続ファイル（環境判定機能付き）
├── env.example.php       # 本番環境設定のテンプレート
└── env.php              # 本番環境設定（GitHubにアップロードされません）
```

## セットアップ手順

### 1. ローカル環境（XAMPP）
- 追加設定は不要です
- `localhost` で自動的にXAMPP設定が使用されます

### 2. 本番環境（さくらサーバー）
1. `config/env.example.php` をコピーして `config/env.php` を作成
2. `env.php` 内の設定値を実際のさくらサーバー情報に変更：
   ```php
   "db_name" => "実際のDB名",
   "db_host" => "mysql999.db.sakura.ne.jp", 
   "db_id"   => "実際のドメイン名",
   "db_pw"   => "実際のパスワード"
   ```
3. `env.php` のみを手動でさくらサーバーにアップロード

## セキュリティ

- `config/env.php` は `.gitignore` で除外されているため、GitHubにアップロードされません
- 秘匿情報（パスワードなど）は安全に管理されます

## 使用方法

データベース接続は従来通り：
```php
require_once 'config/database.php';
$pdo = getDBConnection();
```

環境に応じて自動的に適切な設定が使用されます。
