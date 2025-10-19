# ====================================================
# Laravel Sail  + Git ワークフロー Makefile
# 目的別に開発・本番・不具合対応コマンドを統合
# dev-flow: 開発キャッシュクリア → Gitコミット・プッシュ
# ====================================================

# Sailコマンドを変数化（ローカル環境では php に変更してもOK）
SAIL = ./vendor/bin/sail
BRANCH = main

# =====================================
# 🚀 環境起動（再起動＋フロント起動）
# =====================================
up:
	@echo "🔄 Sail環境を再起動します..."
	$(SAIL) down
	$(SAIL) up -d
	$(SAIL) npm run dev
	@echo "✅ 開発環境が起動しました！"

# =====================================
# 💾 DBマイグレーション（全再構築）
# =====================================
clear:
	@echo "🧩 データベースを再構築中..."
	$(SAIL) artisan migrate:fresh
	$(SAIL) artisan migrate
	@echo "✅ マイグレーション完了！"

# =====================================
# 🧹 開発用キャッシュクリア
# =====================================
dev-clear:
	@echo "🧹 開発用キャッシュクリア中..."
	$(SAIL) artisan config:clear
	$(SAIL) artisan route:clear
	$(SAIL) artisan view:clear
	@echo "✅ 開発キャッシュクリア完了！"

# =====================================
# 🚀 本番デプロイ時（キャッシュ最適化）
# =====================================
deploy:
	@echo "🚀 本番デプロイ処理開始..."
	$(SAIL) artisan optimize:clear
	$(SAIL) artisan config:cache
	$(SAIL) artisan route:cache
	@echo "✅ 本番キャッシュ再生成完了！"

# =====================================
# 🧨 不具合時の全キャッシュリセット
# =====================================
fix:
	@echo "🧨 全キャッシュリセット中..."
	$(SAIL) artisan optimize:clear
	@echo "✅ 全キャッシュ初期化完了！"

# =====================================
# 🧹 開発用キャッシュクリア
# =====================================
dev-clear:
	@echo "🧹 Clearing Laravel caches..."
	$(SAIL) artisan config:clear
	$(SAIL) artisan route:clear
	$(SAIL) artisan view:clear
	@echo "✅ Development caches cleared!"

# =====================================
# 🧭 Git 操作ショートカット
# =====================================
git-push:
	@if [ -z "$(m)" ]; then \
		echo "❌ Please provide a commit message: make git-push m=\"Your message\""; \
		exit 1; \
	fi
	@echo "🚀 Committing and pushing to $(BRANCH)..."
	git add .
	git commit -m "$(m)"
	git push origin $(BRANCH)
	@echo "✅ Push completed!"

# =====================================
# ⚙️ 開発ルーチン（キャッシュクリア → Gitコミット・プッシュ）
# =====================================
dev-flow:
	@if [ -z "$(m)" ]; then \
		echo "❌ Please provide a commit message: make dev-flow m=\"Your message\""; \
		exit 1; \
	fi
	@echo "🔁 Running full development workflow..."
	make dev-clear
	make git-push m="$(m)"
	@echo "🎉 Workflow completed successfully!"

# =====================================
# .PHONY ターゲット
# =====================================
.PHONY: dev-clear git-push dev-flow