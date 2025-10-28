pint-fix:
    just in-be ./vendor/bin/pint --parallel

pint-lint:
    just in-be ./vendor/bin/pint --test

generate:
    just in-be php artisan typescript:transform
