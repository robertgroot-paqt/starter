migrate-fresh:
    just in-be php artisan migrate:fresh

migrate:
    just in-be php artisan migrate

seed:
    just in-be php artisan db:seed
    just sync-permissions

sync-permissions:
    just in-be php artisan app:roles-and-permissions:sync
