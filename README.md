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

- **build:** Changes that affect the build system or external dependencies
- **docs:** Documentation only changes
- **feat:** A new feature
- **impr:** Improved some functionalities/files/logic
- **fix:** A bug fix
- **perf:** A code change that improves performance
- **refactor:** A code change that neither fixes a bug nor adds a feature
- **style:** Changes that do not affect the meaning of the code (white-space, formatting, missing semi-colons, etc)
- **wip:** Work in process

### Scope

Optional, can be one of the following:

- **vue:** Changes to the frontend
- **php:** Changes to the backend (php)
- **sql:** Changes to the database
- **readme:** Changes to the readme file
- **docker:** Changes to docker configs

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

# PHP files usage

## categories.php

`categories.php GET`

Returns the list of all the categories

#### Response:

- HTTP 200: successful

```jsonc
[
  { "id": 1, "name": "TV" },
  { "id": 2, "name": "Smartphone" },
  { "id": 3, "name": "Phon" }
  // An object for every category
]
```

---

`categories.php POST`

Adds a new category, if the user is logged AND is an admin

#### Request

```jsonc
{
  "name": "My New Category" // Name of the new category
}
```

#### Response:

- HTTP 200: successful

- HTTP 403: error

#### Response:

- HTTP 200: successful

```jsonc
[
  { "id": 1, "name": "TV" },
  { "id": 2, "name": "Smartphone" },
  { "id": 3, "name": "Phon" }
  // An object for every category
]
```

---

`categories.php PATCH`

Renames one category, if the user is logged AND is an admin

```jsonc
{
  "id": 3, // Category id to rename
  "name": "My New Category" // New name of the new category
}
```

#### Response:

- HTTP 200: successful

- HTTP 403: error

---

`categories.php?id=[categoryID]`

Deletes one category, if the user is logged AND is an admin

#### Response:

- HTTP 200: successful

- HTTP 403: error

---

## brands.php

Same methods, requests and response messages as `categories.php`. See above for details.

## login.php

`login.php GET`

#### Response:

Format:

```jsonc
{
  "logged": true, // if the user is logged
  "isAdmin": false // if the user is Admin
}
```

Status code:

- HTTP 200: successful

---

`login.php?logout GET`

#### Response:

- HTTP 200: successful

---

`login.php POST`

JSON in post:

```jsonc
{
  "email": "pippo@baudo.it",
  "password": "myPassword"
}
```

#### Response:

- HTTP 200: successful

- HTTP 403: error

## user.php

`user.php GET` (if the user is logged)

#### Response:

Format:

```jsonc
{
  "name": "Pippo",
  "surname": "Baudo",
  "email": "pippo@baudo.it",
  "phoneNumber": "1231231230"
}
```

Status codes:

- HTTP 200: successful

- HTTP 403: error (Not logged)

---

`user.php?all GET` (get all users, only if admin)

#### Response:

Format:

```jsonc
[
  {
    "name": "Pippo",
    "surname": "Baudo",
    "email": "pippo@baudo.it",
    "phoneNumber": "1231231230"
  },
  ...
]
```

Status codes:

- HTTP 200: successful

- HTTP 403: error (Not logged as admin)

---

`user.php POST` = User registration

JSON in post:

```jsonc
{
  "name": "Pippo",
  "surname": "Baudo",
  "email": "pippo@baudo.it",
  "phoneNumber": "1231231230", // Can be of 9 or 10 chars
  "password": "myPassword"
}
```

#### Response:

- HTTP 200: successful

- HTTP 400: general error (Bad Request)

Optional response:

```jsonc
// Example: in case of a registration with an email already in use by someone
{ "error": "E-mail already in use." }
```

---

`user.php PATCH` (if the user is logged)

Sends in POST one or more fields and the server overwrites it

#### Request format:

```jsonc
{
  // The request can contain all the user fields (name, surname, phoneNumber, email)
  "name": "Pippo",
  "phoneNumber": "1231231230"
}
```

- HTTP 200: successful

- HTTP 400: general error (Bad Request)

---

### user.php?address (manage user addresses)

`user.php?address POST` = Add an address to the user's addresses

JSON in post:

```jsonc
{
  "region": 2, // Region id
  "province": 43, // Province id
  "city": 234, // City id,
  "address": "1231231230", // Can be of 9 or 10 chars
  "password": "myPassword"
}
```

#### Response:

- HTTP 200: successful

- HTTP 400: general error (Bad Request)

Optional response:

```jsonc
// Example in case of a registration with an email already taken
{ "error": "E-mail already in use." }
```

---

### user.php?admin (Add new admins)

`user.php?admin POST` (if the user is logged AND is admin)

An admin can add new admins from the existing users.

#### Request format:

```jsonc
{
  "id": 4, // User id
  "isAdmin": true // An admin can toggle the user status between admin/not admin
}
```

#### Response codes

- HTTP 200: successful

- HTTP 403: forbidden (ex. current user not admin)

---

## user.php?cart (User cart management)

`user.php?cart GET`

Gets all the products from the user's cart

#### Response

Format:

Same as `products.php GET`, but the field "quantity" represents the quantity of the product into the cart

Codes:

- HTTP 200: successful

- HTTP 403: error (Not logged)

---

`user.php?cart POST`

Adds a product to the user's cart

#### Request format:

```jsonc
{
  "id": 4, // Product id
  "quantity": 3
}
```

#### Response codes

- HTTP 200: successful

- HTTP 403: error (Not logged)

---

`user.php?cart PATCH`

Changes the selected product's quantity in the user's cart

#### Request format:

```jsonc
{
  "id": 4, // Product id
  "quantity": 5
}
```

#### Response codes

- HTTP 200: successful

- HTTP 400: general error (example: quantity not available)

- HTTP 403: error (Not logged)

---

`user.php?cart?id=[productId] DELETE`

Removes a product from the user's cart

#### Response codes

- HTTP 200: successful (product removed from cart (or it already wasn't there))

- HTTP 403: error (Not logged)

<br>

# products.php

`products.php GET`

Get the list of products bases on a filter

- by title `?q=`
- by category `?c=`
- by brand `?b=`
- by price range `?ps=x&pe=x` (price start and/or price end)

Order by:

- order by price `?sort=price`
- order by title `?sort=title`
- order by sales `?sales` (best sellers / worst sellers)
- Order ASC or DESC `?asc` or `?desc`

Pagination:

- `?p=` (Example ?p=0 page one, it will show an arbitrary amount of products per page)

- Single product: `?id=` (See `products.php?id=` below for details)

#### Response

- HTTP 200: successful

Response format:

```jsonc
{
  "totalResults": 123,
  "productsPerPage": 16,
  "data": [
    {
      "id": 2,
      "title": "My product",
      "description": "...",
      "imageUrl": "my/image/url", // URL to the first image of a product
      "sellPrice": 43.21, // ONLY sell_price
      "quantity": 23, // Products availability
      "categoryId": 1,
      "categoryName": "Smartphone",
      "brandId": 2,
      "brandName": "Samsung"
    },
    {
      // One object for each product
    }
  ]
}
```

---

`products.phpi?id=[productId] GET`

Gets the info of a single product

#### Response

- HTTP 200: successful

Response format:

(similar to `products.php GET`, but with all the images)

```jsonc
{
  "id": 2,
  "title": "My product",
  "description": "...",
  "images": [
    // list to all the images of a product
    "my/image/url1",
    "my/image/url2",
    "my/image/url3"
  ],
  "sellPrice": 43.21, // ONLY sell_price
  "quantity": 23, // Products availability
  "categoryId": 1,
  "categoryName": "Smartphone",
  "brandId": 2,
  "brandName": "Samsung"
}
```

---

`products.php?admin GET`

Same as `products.php GET`, but it also returns recommendedPrice and purchasePrice

#### Response codes

- HTTP 200: successful (User is logged and is admin)

- HTTP 403: error (Not logged)

---

`products.php?admin&id=[productId] GET`

Same as `products.php?id=[number] GET`, but it also returns recommendedPrice and purchasePrice

#### Response codes

- HTTP 200: successful (User is logged and is admin)

- HTTP 403: error (Not logged)

---

`products.php POST`

Adds a new product

#### Request format

```jsonc
{
  "title": "My product",
  "description": "...",
  "purchasePrice": 43.21,
  "sellPrice": 55.99,
  "recommendedPrice": 50.0,
  "quantity": 23, // Products availability
  "categoryId": 1,
  "brandId": 2
}
```

#### Response codes

- HTTP 200: successful (User is logged and is admin)

- HTTP 403: error (Not logged)

---

`products.php POST?set-quantity&id=[productId] POST`

Set quantity of product (user must be logged as admin)

#### Request

```jsonc
{ "quantity": 3 }
```

#### Response codes

- HTTP 200: successful (User is logged and is admin)

- HTTP 403: error (Not logged)

---

`products.php?id=[productId] DELETE`

Deletes a product and its images from the database

#### Response codes

- HTTP 200: successful (User is logged and is admin)

- HTTP 403: error (Not logged)

---

`products.php?id=[productId] PATCH`

Patch data of selected product. You can send only the desired changed values with the **same format** as in POST requests.

#### Response codes

- HTTP 200: successful (User is logged and is admin)

- HTTP 403: error (Not logged)

---

## products.php?image

`products.php?image&id=[productId] POST`

Stores a new image of that product

#### Request:

_// TODO: Define how to send images to the server and store them_

#### Response codes

- HTTP 200: successful (User is logged and is admin)

- HTTP 403: error (Not logged)

---

`products.php?image&id=[productId] DELETE`

Deletes an image from the server

#### Response codes

- HTTP 200: successful (User is logged and is admin)

- HTTP 403: error (Not logged)

<br>

# orders.php (to be defined)

_//TODO: Define orders methods_

`orders.php GET`

Get of the user's orders

---

`orders.php POST`

Add an user's order

---

`orders.php?admin GET`

Get of all the orders (limit them at a defined number? maybe with a parameter)
