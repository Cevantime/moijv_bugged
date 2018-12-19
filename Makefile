sync:
	git pull
	composer install
	make syncdb

install:
	php bin/console doctrine:database:create
	make syncdb

syncdb:
	php bin/console doctrine:migrations:migrate
	php bin/console doctrine:fixtures:load