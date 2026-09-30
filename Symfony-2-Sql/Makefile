# Makefile - à placer dans Symfony-2-Sql/

EXOS := $(wildcard ex*/)

# Restaure .env et .env.dev depuis les fichiers d'exemple (sans écraser l'existant)
env:
	@for dir in $(EXOS); do \
		if [ -f "$$dir.env" ]; then \
			echo "• $$dir.env existe déjà, ignoré"; \
		elif [ -f "$$dir""env_exemple.txt" ]; then \
			cp "$$dir""env_exemple.txt" "$$dir.env"; \
			echo "✔ $$dir.env créé"; \
		else \
			echo "✘ $$dir""env_exemple.txt introuvable"; \
		fi; \
		if [ -f "$$dir.env.dev" ]; then \
			echo "• $$dir.env.dev existe déjà, ignoré"; \
		elif [ -f "$$dir""envdev_exemple.txt" ]; then \
			cp "$$dir""envdev_exemple.txt" "$$dir.env.dev"; \
			echo "✔ $$dir.env.dev créé"; \
		else \
			echo "✘ $$dir""envdev_exemple.txt introuvable"; \
		fi; \
	done

# Sauvegarde .env et .env.dev vers les fichiers d'exemple
save:
	@for dir in $(EXOS); do \
		[ -f "$$dir.env" ] && cp "$$dir.env" "$$dir""env_exemple.txt" && echo "✔ $$dir""env_exemple.txt"; \
		[ -f "$$dir.env.dev" ] && cp "$$dir.env.dev" "$$dir""envdev_exemple.txt" && echo "✔ $$dir""envdev_exemple.txt"; \
	done; true

# Lance composer install dans chaque dossier contenant un composer.json
install:
	@for dir in $(EXOS); do \
		if [ -f "$$dir""composer.json" ]; then \
			echo "=== composer install dans $$dir ==="; \
			(cd "$$dir" && composer install) || echo "✘ échec dans $$dir"; \
		else \
			echo "• $$dir""composer.json introuvable, ignoré"; \
		fi; \
	done

.PHONY: env save install