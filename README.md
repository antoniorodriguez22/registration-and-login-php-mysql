# registration-and-login-php-mysql
Registration and Login system using PHP and MySQL

PHP, alongside with MySQL power a big percentage of the WWW. 

In this project, I am contributing with a very simple registration and login system using these technologies. Some pieces of code,
such as password_hash() function and prepared statements were used thinking about security issues. Password are basically undecryptable(in
case someone breaks into our database, they will not be able to get passwords from users). Also, by using prepared statements, we are
avoiding SQL injection, which could cause damage to our database. 

Our database has the USERTABLE, with values of fullName, userEmail, phoneNumber, and userPass. You could adjust database fields and queries 
according to the needs of your platform. 

I hope this sample code works for you all, especially new developers like myself.


