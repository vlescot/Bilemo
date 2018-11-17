# Bilemo

**[Visit the API Doc](http://bilemo.vincentlescot.fr/api/doc)**  

Bilemo is a REST API built during my [web develpment learning path](https://openclassrooms.com/paths/developpeur-se-d-application-php-symfony) with OpenClassrooms. 

This application is built with Symfony ~4.0 and [ADR (Action-Domain-Responder)](https://youtu.be/y7c-XWLYMVA) architectural pattern.

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/8ff269ebed614438b66a5f632907390a)](https://www.codacy.com/app/vlescot/Bilemo?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=vlescot/Bilemo&amp;utm_campaign=Badge_Grade)[![Maintainability](https://api.codeclimate.com/v1/badges/ba2afc72a93c82232dfa/maintainability)](https://codeclimate.com/github/vlescot/Bilemo/maintainability)

#### Friendly with :  
   1. PSR-0, PSR-1, PSR-2, PSR-4  
   2. Symfony Best Practices  
   3. Doctrine Best Practices
   
## Built with
##### Back-end
* Symfony 4 (Flex)
* Doctrine 
* PHPUnit Bridge 
* JWTAuthentication

## Install
 1. Clone or download the repository into your environment.  
    ```https://github.com/vlescot/bilemo.git  ```
 2. Change the files  *.env.dist* and *phpunit.xml.dist* with your own data :  
 3. Install the database and inject the fixtures :\
    ``` php bin/console doctrine:database:create ``` \
    ```php bin/console doctrine:schema:update --force```\
    ```php bin/console doctrine:fixtures:load --append```
 4. Generate the JWTAuthentication SSH keys \
    ```openssl genrsa -out config/jwt/private.pem -aes256 4096``` \
    ```openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem``` 

 
 ## Test the application
 #### As Bilemo root User POST at  ```/api/token/company ``` 
```json 
{
    "username": "Bilemo",
    "password": "Bilemo"
}
```

 #### As regular User POST at  ```/api/token/user ``` 
```json 
{
    "username": "RegularUser",
    "password": "OpenClassrooms"
}
```
 ## Documentation
 This API project is as documented as possible, so you can find a [full documentation](http://bilemo.vincentlescot.fr/api/doc) of API methods by adding /api/doc at the end of your API URI.
 
&nbsp; 
&nbsp;
Enjoy ! :smile: