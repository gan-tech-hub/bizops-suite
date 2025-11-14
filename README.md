# **BizOpsSuite**  
Laravel + SQLite + Breeze  

顧客管理・予約管理・担当者管理を一元化できる、業務支援統合ツールです。  
中小規模事業者が抱える「顧客情報の分散」「予約・担当調整の煩雑さ」を解消します。  

---

## 🚀 **デプロイURL**  
🔗 https://bizops-suite.onrender.com  

---

## 🛠️ **機能概要**  
✅ 顧客管理（登録・編集・削除・検索）  
✅ 予約管理（日時・担当者・顧客を紐づけ）  
✅ 担当者管理（役職・氏名・ステータス表示）  
✅ ログイン／ログアウト機能（Laravel Breeze認証）  
✅ 管理画面（ダッシュボード）  
✅ レスポンシブ対応（PC／スマホ対応）  

---

## 🖥️ **技術スタック**

### フロントエンド  
- Blade（Laravel標準テンプレートエンジン）  
- Tailwind CSS  

### バックエンド  
- Laravel 11  
- Laravel Breeze（認証機能）  

### データベース  
- SQLite  

### デプロイ先  
- Render（Webサービス／DBホスティング）  

### コード管理  
- Git / GitHub  

### その他ツール・技術  
- Eloquent ORM（DB操作）  
- Vite（ビルドツール）  
- 環境変数管理（.env）  

---

## ⚙️ **セットアップ方法（ローカル）**

1️⃣ リポジトリをクローン  
```bash
git clone https://github.com/gan-tech-hub/bizops-suite.git
cd bizops-suite
```
2️⃣ 依存関係をインストール
```bash
composer install
npm install
```
3️⃣ 環境変数を設定  
ルートに .env を作成し、以下を設定：
```bash
APP_NAME=BizOpsSuite
APP_URL=http://localhost
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite
```
※ database/database.sqlite ファイルを事前に作成しておきましょう。

```bash
touch database/database.sqlite
```
4️⃣ アプリキー生成

```bash
php artisan key:generate
```
5️⃣ マイグレーション実行

```bash
php artisan migrate
```
6️⃣ ローカル起動

```bash
php artisan serve
```
👉ブラウザで http://localhost:8000 にアクセス。

---

## 📝 ライセンス
MIT

---

## 👤 作成者  
桜庭祐斗  
🔗 GitHub - gan-tech-hub

---

## 📷 スクリーンショット例  
以下は画面のイメージ例です：  
※各画面（顧客一覧・予約登録・担当者管理・ダッシュボード等）のスクリーンショットを掲載予定