# Auckland Unlimited Backend/DevOps skills assessment

## Project Details
- Project mabe using Laravel 8 with JWT Auth;
- GRUD for inventory management
- Postman Documentation with all endpoints
- Simple Docker running PHP Server (artisan serve) and mySQL

---

## Endpoints - USERS
* POST /api/register (Register new user)
    * Mandatory Fields: Name, E-mail, Password

* POST /api/login (Login and get the JWT token)
    * Mandatory Fields: E-mail, Password

* GET /api/me (Get user details)
    * Auth: Bearer {{TOKEN}}

* GET /api/logout (Get user details)
    * Auth: Bearer {{TOKEN}}

---

## Endpoints - Items
* GET /api/items (List all Items that belongs to the user)
    * Auth: Bearer {{TOKEN}}

* POST /api/items (List all Items that belongs to the user)
    * Auth: Bearer {{TOKEN}}
    * Mandatory Fields: Name (string), Description (string), Quantity (Integer)

* GET /api/items/{{id}} (Show all items details)
    * Auth: Bearer {{TOKEN}}

* PUT /api/items/{{id}} (Edit items details)
    * Auth: Bearer {{TOKEN}}
    * Mandatory Fields: Name (string), Description (string), Quantity (Integer)

* PUT /api/items/quantity/{{id}} (Edit only the item's quantity)
    * Auth: Bearer {{TOKEN}}
    * Mandatory Fields: Quantity (Integer)

* DELETE /api/items/{{id}} (Delete Item)
    * Auth: Bearer {{TOKEN}}

---

## How to Run

Clone the project, install the packages using composer and rename the .env file
```sh
git clone https://github.com/grfbr/Auckland-Unlimited-Test.git project-test
cd project-test
mv .env.example .env
composer install
```

Build and Run the docker using the file docker-compose.yml
```sh
docker-compose up -d
```

Get the Docker Container ID to run the Migrations
```sh
docker ps
```

With the Container ID, run the docker exec to open the bin/sh
```sh
docker exec -it {{DOCKER_ID_HERE}} /bin/sh
```
As the last step, execute Artisan to run the migrations
```sh
php artisan migrate
```

If everything is running fine, you can access the API using the address:
```sh
http://127.0.0.1:8880
```

---

## How to Run the tests

Get the Docker Container ID to run the Migrations
```sh
docker ps
```

With the Container ID, run the docker exec to open the bin/sh
```sh
docker exec -it {{DOCKER_ID_HERE}} /bin/sh
```

To run the PHPUnit test, use the following command
```sh
php artisan test
```

You will see something like:
![ss-tests](https://i.ibb.co/1sQGdhk/Screen-Shot-2021-07-18-at-10-42-41-PM.png)
