FROM php:7.4.33-apache 
WORKDIR /var/www/html
RUN apt update -y && apt upgrade -y
RUN docker-php-ext-install mysqli
RUN docker-php-ext-enable mysqli 
