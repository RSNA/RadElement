FROM php:5.6-apache

COPY . /srv/app
COPY vhost.conf /etc/apache2/sites-available/000-default.conf

RUN apt-get update \
  && apt-get install -y nano \
  && docker-php-ext-install pdo pdo_mysql mysql mysqli

RUN chown -R www-data:www-data /srv/app \
    && a2enmod rewrite

WORKDIR /srv/app