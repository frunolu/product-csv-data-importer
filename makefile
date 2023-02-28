up:
	docker-compose up -d --build --force-recreate --remove-orphans

down:
	docker-compose down --volumes --remove-orphans

composer-install:
	docker-compose exec php su --command="composer -n install --prefer-dist --ignore-platform-req=ext-zip" www-data

cache-clean:
	git clean -fdX project/temp/

start-project: up composer-install

import-product:
	docker-compose exec php su --command="bin/console import:product $(input)" www-data

