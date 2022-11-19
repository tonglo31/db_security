FROM php:7.4.33-apache 
RUN apt update -y && apt upgrade -y
RUN docker-php-ext-install mysqli
RUN docker-php-ext-enable mysqli 

FROM docker.elastic.co/beats/filebeat:7.14.2
WORKDIR /usr/share/filebeat
USER root
RUN chown root filebeat.yml