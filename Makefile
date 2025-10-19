# ====================================================
# Laravel Sail + Git ワークフロー Makefile
# dev-flow: 開発キャッシュクリア → Gitコミット・プッシュ
# ====================================================

SAIL = ./vendor/bin/sail
BRANCH = main

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
