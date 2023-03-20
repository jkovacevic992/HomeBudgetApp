Home Budget App

Installation:

- composer install
- configure .env.local
- generate lexik SSL keys: php bin/console lexik:jwt:generate-keypair
- create database: php bin/console doctrine:database:create
- run migrations: php bin/console doctrine:migrations:migrate
- load category fixtures: php bin/console doctrine:fixtures:load
- start Symfony server: symfony server:start

Documentation is available on route /api/doc, e.g. http://localhost:8000/api/doc

Every user you create will start with balance of 5000.

Users can create categories, expenses and earnings.

Earnings and expenses are tied to users so when you request to see all expenses, they will be user specific.

Expenses are tied to categories. 

You can run the basic tests with: php bin/phpunit



