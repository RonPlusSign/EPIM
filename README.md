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

#### ⚠️ The first time the containers are started you should wait a few minutes to let mariadb initialize the database.

<br>

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
require_once __DIR__ . '/../lib/Bootstrap.php';
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

Table name: snake_case, singular, lowercase (es. product_image). ---
Column name: snake_case, lowercase (es. postal_code).

Always define foreign and primary keys as CONSTRAINT
Costraints conventions:

```sql
CONSTRAINT `this_table_fk_referenced_table` FOREIGN KEY(column) REFERENCES [...];
CONSTRAINT `this_table_pk` PRIMARY KEY(columns);
CONSTRAINT `this_table_unique` UNIQUE(columns);    # You can also use `column_name_unique`
```

---

### PHP

Every .php file should have the first letter capitalized.

---

### VUE

Every file should be capitalized. Views and components should go in the folder with the same name (/views and /components).
Every **component name** should be prefixed with the letter 'E' (es. EFooter.vue).

## Endpoints

- [Categories](#categories.php)
- [Brands](#brands.php)
- [Login](#login.php)
- [User](#user.php)
- [Products](#products.php)
- [Address](#address.php)
- [Order](#orders.php)

---

## categories.php

### GET

Returns the list of all the categories

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

---

### POST

Adds a new category, if the user is logged AND is an admin

##### Request:

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

---

### PATCH

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

---

### DELETE

> `?id=[categoryID]`

Deletes a category, the user **must** be admin

##### Response:

- HTTP 200: successful

- HTTP 403: error

---

## brands.php

Same methods, requests and response messages as `categories.php`. See above for details.

---

## login.php

### GET

> `no query`

##### Response:

```jsonc
{
  "logged": true, // if the user is logged
  "isAdmin": false // if the user is Admin
}
```

- HTTP 200: successful

---

> `login.php?logout`

##### Response:

- HTTP 200: successful

---

### POST

```jsonc
{
  "email": "pippo@baudo.it",
  "password": "myPassword"
}
```

##### Response:

- HTTP 200: successful

- HTTP 403: error

---

## user.php

### GET

> `no query`

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

---

> `user.php?all`

Returns all the users.

#### user **must** be admin to use this

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

##### Response codes:

- HTTP 200: successful

- HTTP 403: error (Not logged as admin)

---

### POST

#### User registration

```jsonc
{
  "name": "Pippo",
  "surname": "Baudo",
  "email": "pippo@baudo.it",
  "phoneNumber": "1231231230", // Can be of 9 or 10 chars
  "password": "myPassword"
}
```

##### Response codes:

- HTTP 200: successful

- HTTP 400: general error (Bad Request)

##### Optional response:

```jsonc
// Example: in case of a registration with an email already in use by someone
{ "error": "E-mail already in use." }
```

---

### PATCH

#### User profile changes

##### Request:

```jsonc
{
  "name": "Pippo",
  "surname": "Baudo",
  "email": "pippo@baudo.it"
}
```

##### Response codes:

- HTTP 200: successful

- HTTP 400: general error

- HTTP 403: error (Not logged)

##### Optional response:

```jsonc
// Example in case of a patch with an email already taken
{ "error": "E-mail already in use." }
```

## user.php?cart (Cart management)

### GET

#### Gets all the products from the user's cart

##### Response:

_Same as products.php `GET`, but with the field "selectedQuantity"that represents the quantity of the product into the cart_

##### Response codes:

- HTTP 200: successful

- HTTP 403: error (Not logged)

---

### POST

#### Adds a product to the user's cart

##### Request: format:

```jsonc
{
  "id": 4, // Product id
  "quantity": 3
}
```

##### Response codes:

- HTTP 200: successful

- HTTP 400: general error (example: quantity not available)

- HTTP 403: error (Not logged)

---

### PATCH

#### Changes the selected product's quantity in the user's cart

##### Request:

```jsonc
{
  "id": 4, // Product id
  "quantity": 5
}
```

##### Response:

- HTTP 200: successful

- HTTP 400: general error (example: quantity not available)

- HTTP 403: error (Not logged)

---

### DELETE

> `?cart&id=[productId]`

#### Removes a product from the user's cart

##### Response:

- HTTP 200: successful (product removed from cart (or it already wasn't there))

- HTTP 403: error (Not logged)

---

## user.php?address

### GET

#### Get all the USERS's addresses

```jsonc
[
  {
    "id": 13, // Address id
    "cityName": "Firenze", // City name
    "city": 234, // City id
    "street": "Via dei Polli",
    "houseNumber": 123,
    "postalCode": 54100,
    "phoneNumber": "123123123" // Phone number associated with that address
  },
  {
    "id": 123, // Address id
    "cityName": "Novara", // City name
    "city": 123, // City id
    "street": "Via delle galline",
    "houseNumber": 321,
    "postalCode": 21300,
    "phoneNumber": "456456456" // Phone number associated with that address
  }
]
```

##### Response:

- HTTP 200: successful

- HTTP 403: error (Not Logged)

---

### POST

#### Add an address to the user's addresses

```jsonc
{
  "city": 234, // City id
  "street": "Via dei Polli",
  "houseNumber": 123,
  "postalCode": 54100,
  "phoneNumber": "123123123" // Phone number associated with that address
}
```

##### Response:

- HTTP 200: successful

- HTTP 400: general error (Bad Request)

---

### DELETE

#### DELETE a user's address

> `?id=[address-id]`

##### Response:

- HTTP 200: successful

- HTTP 403: error (Not Logged)

---

### PATCH

#### Modify a user's address

```jsonc
{
  "id": 123,
  "street": "Via dei pollai",
  "houseNumber": 123
}
```

##### Response:

- HTTP 200: successful

- HTTP 400: general error (Bad Request)

- HTTP 403: error (Not logged)

---

## user.php?admin

### POST

#### An **admin** can add new admins from the existing users (user **must be admin** to perform this action).

##### Request:

```jsonc
{
  "id": 4, // User id
  "isAdmin": true // An admin can toggle the user status between admin/not admin
}
```

##### Response:

- HTTP 200: successful

- HTTP 403: forbidden (ex. current user not admin)

---

## products.php

### GET

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

##### Response:

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
      "images": ["...",...],
      "sell_price": 43.21, // ONLY sell_price
      "quantity": 23, // Products availability
      "category_id": 1,
      "category": "Smartphone",
      "brand_id": 2,
      "brand": "Samsung"
    },
    {
      // One object for each product
    }
  ]
}
```

##### side note: if the user is logged as admin it will return also recommended_price and purchase-price

---

> `products.php?id=[productId] GET`

#### Gets the info of a single product

##### Response:

- HTTP 200: successful

##### Response: format:

(similar to `products.php GET`)

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
  "sell_price": 43.21, // ONLY sell_price
  "quantity": 23, // Products availability
  "category_id": 1,
  "category": "Smartphone",
  "brand_id": 2,
  "brand": "Samsung"
}
```

##### side note: if the user is logged as admin it will return also recommended_price and purchase_price

---

### POST

#### Adds a new product (user must be **admin**)

##### Request: format

```jsonc
{
  "title": "My product",
  "description": "...",
  "purchase_price": 43.21,
  "sell_price": 55.99,
  "recommended_price": 50.0,
  "quantity": 23, // Products availability
  "category_id": 1,
  "brand_id": 2
}
```

##### Response codes:

- HTTP 200: successful (User is logged and is admin)

- HTTP 403: error (Not logged)

---

### DELETE

> `products.php?id=[productId]`

Deletes a product and its images from the database

##### Response codes:

- HTTP 200: successful (User is logged and is admin)

- HTTP 403: error (Not logged)

---

### PATCH

> `products.php?id=[productId] PATCH`

#### Patch data of selected product. You can send only the desired changed values with the **same format** as in POST requests.

##### Response codes:

- HTTP 200: successful (User is logged and is admin)

- HTTP 403: error (Not logged)

---

## products.php?image

### POST

> `products.php?image&id=[productId]`

#### Stores a new image of that product

##### Request:

_// TODO: Define how to send images to the server and store them_

##### Response codes:

- HTTP 200: successful (User is logged and is admin)

- HTTP 403: error (Not logged)

---

### DELETE

> `products.php?image&id=[productId]`

Deletes an image from the server

##### Response codes:

- HTTP 200: successful (User is logged and is admin)

- HTTP 403: error (Not logged)

---

## address.php

### GET

> `address.php?regions`

#### Get of the regions, provinces and cities of Italy

Returns an array with all the regions of Italy

##### Response:

```jsonc
[
  {
    "id": 2,
    "name": "Toscana"
  },
  {
    "id": 3,
    "name": "Emilia Romagna"
  },
  {
    // ... other regions
  }
]
```

---

> `address.php?regions?id=[region-id]`

#### Returns the region with that id

##### Response:

```jsonc
{
  "id": 2,
  "name": "Toscana"
}
```

---

> `address.php?provinces`

#### Returns an array with all the provinces of Italy

##### Response:

```jsonc
[
  {
    "id": 2,
    "name": "Firenze",
    "region": 12
  },
  {
    "id": 3,
    "name": "Bologna",
    "region": 13
  },
  {
    // ... other provinces
  }
]
```

---

> `address.php?provinces?region=[region-id]`

#### Returns an array with all the provinces of a specific region of Italy

##### Response:

```jsonc
[
  {
    "id": 2,
    "name": "Firenze"
  },
  {
    "id": 3,
    "name": "Prato"
  },
  {
    // ... other provinces of that region
  }
]
```

---

> `address.php?provinces?id=[province-id]`

#### Returns the province with that id

##### Response:

```jsonc
{
  "id": 2,
  "name": "Firenze",
  "region": 3 // Province's region id
}
```

---

> `address.php?cities`

#### Returns an array with all the cities of Italy

##### Response:

```jsonc
[
  {
    "id": 2,
    "name": "Firenze",
    "province": 6
  },
  {
    "id": 3,
    "name": "Arezzo",
    "province": 9
  },
  {
    // ... other cities of Italy
  }
]
```

---

> `address.php?cities?province=[province-id]`

#### Returns an array with all the cities of a specific province

##### Response:

```jsonc
[
  {
    "id": 2,
    "name": "Firenze"
  },
  {
    "id": 3,
    "name": "Scandicci"
  },
  {
    // ... other provinces of that region
  }
]
```

---

> `address.php?cities?id=[city-id]`

#### Returns the city with that id

##### Response:

```jsonc
{
  "id": 2,
  "name": "Scandicci",
  "province": 3 // City's province id
}
```

---

## orders.php

### GET

#### Get of the **user**'s orders

##### Response:

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

##### Response codes:

- HTTP 200: successful

- HTTP 403: error (Not logged)

---

> `orders.php?purchase&address=id`

###### address=id is the id of the user address

#### Add an user's order

##### Request: empty (no body)

##### Response codes:

- HTTP 200: successful

- HTTP 403: error (Not logged)

---

> `orders.php?admin`

#### Get of all the orders (user **must** be admin)

##### Response:

```jsonc
[
  // Orders array
  {
    // Every object is an order
    // The order has the same format as orders.php GET, but every order also has the references to the user

    // ... order data (= to orders.php GET)

    // user data
    "user": {
      "user_id": 33,
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
