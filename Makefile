install:
	composer install --no-scripts
	symfony console doctrine:database:create
	symfony console doctrine:migrations:migrate
	symfony console doctrine:fixtures:load
	symfony console cache:clear
	setfacl -dR -m u:$(uid):rwX .
	setfacl -R -m u:$(uid):rwX .
