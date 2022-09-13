# Bilemo

[![SymfonyInsight](https://insight.symfony.com/projects/e25406eb-c823-4c61-9c71-a674bf0bb1e6/big.svg)](https://insight.symfony.com/projects/e25406eb-c823-4c61-9c71-a674bf0bb1e6)

# Initialise project

## Versions
* PHP 8.1.6
* Symfony 5.3.0
* Doctrine 2.7.1
* Mysql  10.4.24-MariaDB

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

3. Download and install packages dependencies

````
yarn install
````

or

````
npm install
````

4. Build from asset

````
composer require symfony/apache-pack
````


5. Using Database

Update DATABASE_URL .env file with your database configuration:


DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name


6. Create database

````
symfony console d:d:c 

````

Create database structure

````
symfony console m:m

````

7. Load datas fixtures

````
symfony console d:f:l

````

8. Start server

````
symfony serve

````
9. Documentation en ligne :

Une interface pour documenter l'API et tester les différentes routes a été réalisée à l'aide de NelmioApiDocBundle.
