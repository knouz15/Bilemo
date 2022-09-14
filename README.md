# Bilemo

[![SymfonyInsight](https://insight.symfony.com/projects/e25406eb-c823-4c61-9c71-a674bf0bb1e6/big.svg)](https://insight.symfony.com/projects/e25406eb-c823-4c61-9c71-a674bf0bb1e6)

# Initialize project

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

````
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name
````

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

8. Generate files of JWT certificate
 
 ````
$ mkdir -p config/jwt
````

````
$ openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
````

````
$ openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
````

9.  Using JWT

Edit .env file with your configuration parameters:

````
###> lexik/jwt-authentication-bundle ###

JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem

JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem

JWT_PASSPHRASE=VotrePassePhrase

###< lexik/jwt-authentication-bundle ###
````  
  
10. Start server

````
symfony serve
````

11. Documentation :

An interface to document the API and test the different routes has been made using NelmioApiDocBundle.
