# StoreManager

## StoreManager
`StoreManager` は Laravel を使用したストア管理システムです。  
商品管理、注文管理、ユーザー管理などの機能を備えたシンプルなウェブアプリケーションです。

---

## 機能一覧
- **ユーザー管理**（ログイン・登録・認証）
- **商品管理**（商品登録・編集・削除）
- **注文管理**（注文履歴の閲覧・更新）
- **ダッシュボード**（管理者向け統計情報）

---

## 使用技術
- **バックエンド**: Laravel 11
- **データベース**: MySQL
- **フロントエンド**: Blade, Bootstrap
- **認証**: Laravel Auth
- **環境変数管理**: `.env`

---

## インストール方法

### 1️⃣ クローンする
```bash
git clone https://github.com/yotsuda-koki/StoreManager.git
cd StoreManager
```

### 2️⃣ `.env` を設定
```bash
cp .env.example .env
```
`.env` を編集し、データベースの情報を設定してください。

### 3️⃣ 必要なパッケージをインストール
```bash
composer install
npm install
```

### 4️⃣ アプリキーの生成
```bash
php artisan key:generate
```

### 5️⃣ データベースの作成 & マイグレーション
```bash
php artisan migrate --seed
```

### 6️⃣ サーバーを起動
```bash
php artisan serve
```
ブラウザで `http://127.0.0.1:8000` にアクセスしてください。

---

## 📂 フォルダ構成
```
StoreManager/
├── app/            # アプリケーションロジック
├── bootstrap/      # フレームワーク起動処理
├── config/         # 設定ファイル
├── database/       # データベース関連（マイグレーションなど）
├── public/         # 公開ディレクトリ
├── resources/      # ビュー・アセットなど
├── routes/         # ルーティング定義
├── storage/        # キャッシュ・ログ等
├── tests/          # テストコード
├── .env.example    # 環境変数のサンプル
├── composer.json   # PHP パッケージ管理
├── package.json    # Node.js パッケージ管理
└── README.md       # プロジェクトの説明
```

---
