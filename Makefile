up:
	./vendor/bin/sail down
	./vendor/bin/sail up -d
	./vendor/bin/sail npm run dev

clear:
	./vendor/bin/sail artisan migrate:fresh
	./vendor/bin/sail artisan migrate

cache-clear:
	./vendor/bin/sail artisan optimize:clear
	./vendor/bin/sail artisan cache:clear
	./vendor/bin/sail artisan config:clear
	./vendor/bin/sail artisan route:clear
	./vendor/bin/sail artisan view:clear
