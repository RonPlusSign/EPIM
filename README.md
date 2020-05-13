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

# Endpoints

- [Categories](#categories.php)
  - [GET](#get)
  - [POST](#post)
  - [PATCH](#patch)
  - [DELETE](#delete)
- [Brands](#brands.php)
- [Login](#login.php)
- [User](#user.php)
- [Product](#products.php)
- [Order](#orders.php)

## categories.php

#### GET

Returns the list of all the categories

#### Response:

```jsonc
[
  { "id": 1, "name": "TV" },
  { "id": 2, "name": "Smartphone" },
  { "id": 3, "name": "Phon" }
  // An object for every category
]
```

- HTTP 200: successful

<br>

#### POST

Adds a new category, if the user is logged AND is an admin

##### Request

```jsonc
{
  "name": "My New Category" // Name of the new category
}
```

##### Response:

```jsonc
[
  { "id": 1, "name": "TV" },
  { "id": 2, "name": "Smartphone" },
  { "id": 3, "name": "Phon" }
  // An object for every category
]
```

- HTTP 200: successful

- HTTP 403: error

<br>

#### PATCH

Renames one category, if the user is logged AND is an admin

```jsonc
{
  "id": 3, // Category id to rename
  "name": "My New Category" // New name of the new category
}
```

##### Response:

- HTTP 200: successful

- HTTP 403: error

<br>

#### DELETE

> ?id=[categoryID]

Deletes a category, the user **must** be admin

##### Response:

- HTTP 200: successful

- HTTP 403: error

<br>

## brands.php

Same methods, requests and response messages as `categories.php`. See above for details.

<br>

## login.php

#### GET

> no query

##### Response:

```jsonc
{
  "logged": true, // if the user is logged
  "isAdmin": false // if the user is Admin
}
```

- HTTP 200: successful

<br>

> ?logout

##### Response:

- HTTP 200: successful

<br>

#### POST

```jsonc
{
  "email": "pippo@baudo.it",
  "password": "myPassword"
}
```

##### Response:

- HTTP 200: successful

- HTTP 403: error

<br>

## user.php

#### GET

> no query

##### Response:

```jsonc
{
  "name": "Pippo",
  "surname": "Baudo",
  "email": "pippo@baudo.it",
  "phoneNumber": "1231231230"
}
```

- HTTP 200: successful

- HTTP 403: error (Not logged)

<br>

> ?all

###### user **must** be admin to use this

##### Response:

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

- HTTP 200: successful

- HTTP 403: error (Not logged as admin)

<br>

> ?cart

###### Gets all the products from the user's cart

#### Response

_Same as products.php `GET`, but the field "quantity" represents the quantity of the product into the cart_

- HTTP 200: successful

- HTTP 403: error (Not logged)

<br>

#### POST

> User registration

```jsonc
{
  "name": "Pippo",
  "surname": "Baudo",
  "email": "pippo@baudo.it",
  "phoneNumber": "1231231230", // Can be of 9 or 10 chars
  "password": "myPassword"
}
```

##### Response:

- HTTP 200: successful

- HTTP 400: general error (Bad Request)

##### Optional response:

```jsonc
// Example: in case of a registration with an email already in use by someone
{ "error": "E-mail already in use." }
```

<br>

> ?address

###### Add an address to the user's addresses

```jsonc
{
  "region": 2, // Region id
  "province": 43, // Province id
  "city": 234, // City id,
  "address": "1231231230", // Can be of 9 or 10 chars
  "password": "myPassword"
}
```

##### Response:

- HTTP 200: successful

- HTTP 400: general error (Bad Request)

##### Optional response:

```jsonc
// Example in case of a registration with an email already taken
{ "error": "E-mail already in use." }
```

<br>

> ?admin

###### An admin can add new admins from the existing users (user must be admin to perform this action).

##### Request:

```jsonc
{
  "id": 4, // User id
  "isAdmin": true // An admin can toggle the user status between admin/not admin
}
```

##### Response

- HTTP 200: successful

- HTTP 403: forbidden (ex. current user not admin)

<br>

> ?cart

###### Adds a product to the user's cart

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

<br>

#### PATCH

> no query

###### user must be logged in order to work

##### Request:

```jsonc
{
  // The request can contain all the user fields (name, surname, phoneNumber, email)
  "name": "Pippo",
  "phoneNumber": "1231231230"
}
```

##### Response:

- HTTP 200: successful

- HTTP 403: User not logged

<br>

> ?cart

###### Changes the selected product's quantity in the user's cart

##### Request:

```jsonc
{
  "id": 4, // Product id
  "quantity": 5
}
```

##### Response

- HTTP 200: successful

- HTTP 400: general error (example: quantity not available)

- HTTP 403: error (Not logged)

<br>

#### DELETE

> ?cart?id=[productId]

###### Removes a product from the user's cart

#### Response

- HTTP 200: successful (product removed from cart (or it already wasn't there))

- HTTP 403: error (Not logged)

<br>

## products.php

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

# orders.php

`orders.php GET`

Get of the user's orders

#### Response

```jsonc
[
  // orders array
  {
    "id": 3,
    "date": "2020-03-13", // time of the order (timestamp)
    "hour": "14:12:54",
    "address": "Via di Pippo, 13 (FI)",
    "phoneNumber": 123123123,
    "shippingCost": 12.3,
    "products": [
      // Array of products
      {
        "productId": 3,
        "productTitle": "My product",
        "quantity": 7,
        "price": 33.2,
        "brandName": "Samsung"
      },
      {
        // Every object is a product
      }
    ],
    "status": "Spedito"
  },
  {
    // Every object is an order
  }
]
```

#### Response codes

- HTTP 200: successful (User is logged and is admin)

- HTTP 403: error (Not logged)

---

`orders.php?purchase GET`

Add an user's order

#### Request: empty (no body)

#### Response codes

- HTTP 200: successful (User is logged and is admin)

- HTTP 403: error (Not logged)

---

`orders.php?admin GET`

Get of all the orders

```jsonc
[
  // Orders array
  {
    // Every object is an order
    // The order has the same format as orders.php GET, but every order also has the references to the user

    // ... order data (= to orders.php GET)

    // user data
    "user": {
      "id": 33,
      "name": "Pippo",
      "surname": "Baudo",
      "email": "pippo@baudo.it"
    }
  },
  {
    // ...
  }
]
```
