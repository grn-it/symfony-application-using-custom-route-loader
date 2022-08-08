install:
	composer install
	symfony console doctrine:database:create
	symfony console doctrine:migrations:migrate
	symfony console doctrine:fixtures:load
	setfacl -dR -m u:$(uid):rwX .
	setfacl -R -m u:$(uid):rwX .
