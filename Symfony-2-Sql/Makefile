EXERCISES := $(wildcard ex*)

setup-env:
	@for dir in $(EXERCISES); do \
		if [ -d "$$dir" ]; then \
			cp .env.example "$$dir/.env"; \
			echo "✓ .env créé dans $$dir"; \
		fi \
	done

docker-compose:
	@echo "Création du fichier compose.yaml..."
	@printf '%s\n' \
	'services:' \
	'  mysql:' \
	'    image: mysql:8.0' \
	'    environment:' \
	'      MYSQL_ROOT_PASSWORD: root' \
	'    ports:' \
	'      - "3306:3306"' \
	'' \
	'  phpmyadmin:' \
	'    image: phpmyadmin:latest' \
	'    environment:' \
	'      PMA_HOST: mysql' \
	'      PMA_PORT: 3306' \
	'    ports:' \
	'      - "8080:80"' \
	'    depends_on:' \
	'      - mysql' \
	> compose.yaml
	@echo "✓ compose.yaml créé avec succès !"

composer-install:
	@for dir in $(EXERCISES); do \
		if [ -f "$$dir/composer.json" ]; then \
			echo "→ Installation de Composer dans $$dir"; \
			cd "$$dir" && composer install; \
		fi \
	done

up:
	docker compose up -d
