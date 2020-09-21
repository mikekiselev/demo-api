CREATE DATABASE api_demo;
GRANT ALL PRIVILEGES ON api_demo.* TO 'api_user'@'%' IDENTIFIED BY 'SomePassw0rd';
GRANT ALL PRIVILEGES ON api_demo.* TO 'api_user'@'localhost';
flush privileges;
