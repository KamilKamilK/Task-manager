**Welcome in Task Manager application**

This application was created to manage tasks. After registration, you will receive activation link on your email, 
and you can easily add, edit, delete tasks. 

**Download Composer dependencies**

Make sure you have [Composer installed](https://getcomposer.org/download/)
and then run:

```
composer install
```

**Setup the Database**
Application is setup on Docker containers
In command line paste command :
```
 docker-compose up -d
```
This command will initiate MqSQL database and Mailer service.

Next commands will create tables in database and populate then with fake values.

```
symfony console doctrine:fixtures:load
```

**Setup the Server**
In command line paste command :

```
symfony serve -d
```

**Now check out the site at `https://localhost:8001`**

Now you need to register new client account or use account created as example. 

**EXAMPLE ACCOUNT**
```
email: example@account.com
password: enter
```
HAVE FUN !!!

