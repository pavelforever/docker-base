FROM php:8.1.12-zts-bullseye

RUN apt update -y && apt upgrade -y && apt install -y unzip git acl vim mc nodejs npm
RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

COPY ./common/php/conf.d /usr/local/etc/php/conf.d

ENV COMPOSER_ALLOW_SUPERUSER 1

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --quiet

RUN composer require --dev deployer/deployer && alias dep='vendor/bin/dep'
RUN echo 'alias dep="vendor/bin/dep"' >> ~/.bashrc
RUN echo 'alias ll="ls -la"' >> ~/.bashrc

WORKDIR /app/www
