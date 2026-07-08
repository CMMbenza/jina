# JINA

Plateforme de carte de visite numérique.

## Installation

```bash
git clone https://github.com/votre-compte/jina.git
```

Créer le fichier `.env`

```bash
cp .env.example .env
```

Créer la base de données :

```
jina_db
```

Importer le fichier SQL.

Configurer :

```
DB_HOST
DB_DATABASE
DB_USERNAME
DB_PASSWORD
```

Lancer le serveur :

```bash
php -S localhost:8000
```