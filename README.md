# E.P.I.M.
## E-Commerce Platform ITIS Meucci

Web app of an e-commerce website, with user registation, login, admin, products and orders management. 

---

### Folder Structure (self-explanatory)
 - Backend
 - Frontend
 - Database

---

### Enviroment set-up

The project integrates a docker enviroment. To launch it enter:
```bash
docker-compose -f "docker-compose.yml" up  --build
```

#### ⚠️ The first time the containers are started you should wait a few minutes to let mariadb initialize the database. <br>

If you wish to reset (drop) the database launch this script (it should also be valid for linux):

```cmd
.\rebuilddb.ps1
```

<br>

Available services in the container:

 - Vue.js server: **`localhost:8080`**
 - phpMyAdmin: `pma.localhost:8080`
 - traefik dashboard: `localhost:8001`


---

## PHP Files

At the start of every PHP file should import `Bootstrap.php`:

```php
require_once __DIR__ . '/../lib/cd';
```

This will automatically set-up production/debug mode and import 'Database.php'.

#### Connecting to the database 

To connect to the db there is already a PDO connection established in 'Database.php' so everything you need to do is get the PDO:

```php
Database::$pdo;
```

---

## Commit format

```
<type>(<scope>): <subject>
<BLANK LINE>
<body>
<BLANK LINE>
<footer>
```

### Type
Must be one of the following:

* **build:** Changes that affect the build system or external dependencies
* **docs:** Documentation only changes
* **feat:** A new feature
* **impr:** Improved some functionalities/files/logic
* **fix:** A bug fix
* **perf:** A code change that improves performance
* **refactor:** A code change that neither fixes a bug nor adds a feature
* **style:** Changes that do not affect the meaning of the code (white-space, formatting, missing semi-colons, etc)
* **wip:** Work in process

### Scope
Optional, can be one of the following:

* **vue:** Changes to the frontend
* **php:** Changes to the backend (php)
* **sql:** Changes to the database
* **readme:** Changes to the readme file
* **docker:** Changes to docker configs

[conventionalcommits.org](https://www.conventionalcommits.org/en/v1.0.0/) -
[angular specs](https://github.com/angular/angular/blob/22b96b9/CONTRIBUTING.md#-commit-message-guidelines)

---

## Code conventions

### SQL

Table name: snake_case, singular, lowercase (es. product_image). <br>
Column name: snake_case, lowercase (es. postal_code).

Always define foreign and primary keys as CONSTRAINT
Costraints conventions:

```sql
CONSTRAINT `this_table_fk_referenced_table` FOREIGN KEY(column) REFERENCES [...];
CONSTRAINT `this_table_pk` PRIMARY KEY(columns);
CONSTRAINT `this_table_unique` UNIQUE(columns);    # You can also use `column_name_unique`
```

<br>

### PHP

Every .php file should have the first letter capitalized.

<br>

### VUE

Every file should be capitalized. Views and components should go in the folder with the same name (/views and /components).
Every **component name** should be prefixed with the letter 'E' (es. EFooter.vue).
