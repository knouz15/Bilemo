# Bilemo

[![SymfonyInsight](https://insight.symfony.com/projects/e25406eb-c823-4c61-9c71-a674bf0bb1e6/big.svg)](https://insight.symfony.com/projects/e25406eb-c823-4c61-9c71-a674bf0bb1e6)


This project is deployed on 

````
https://bilemo.hajbensalem.fr/api/doc

````


# Initialize project locally

## Versions
* PHP 8.1.6
* Symfony 6.1.4
* Doctrine 2.7.1
* mariadb-10.4.24

## Requirement
* PHP
* Symfony 
* Composer


## Steps

1. Clone the project repository

````
git clone https://github.com/knouz15/Bilemo.git
````

2. Download and install Composer dependencies

```
composer install
```

3. Configure Database

Update DATABASE_URL .env.local file with your database configuration:

````
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name
````

4. Create database
````

symfony console d:d:c 
````

Create database structure

````
symfony console d:m:m
````

5. Load datas fixtures

````
symfony console d:f:l
````

6. Generate the SSL keys

````
$ php bin/console lexik:jwt:generate-keypair
````
  
7. Start server

````
symfony serve
````

8. Installing Postman

To interact with the APIs, you can install Postman:

````
- Postman (https://www.postman.com/)
````

Postman Tutorials:

````
- https://www.postman.com/resources/videos-tutorials/

````

To test the api locally, you could also import, in your postman, the collection in this file (located at the root of this project):

````
- BilemoAPI.postman_collection.json
````

9. Documentation

An interface to document the API and test the different routes has been made using NelmioApiDocBundle.

You can access the API documentation locally at the following address:

````
- http://127.0.0.1:8000/api/doc

````
