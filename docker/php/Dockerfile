FROM php:7.4-fpm

RUN apt update
RUN apt install -y curl zip libzip-dev unzip imagemagick imagemagick-common
RUN apt update


ADD https://raw.githubusercontent.com/mlocati/docker-php-extension-installer/master/install-php-extensions /usr/local/bin/

RUN chmod uga+x /usr/local/bin/install-php-extensions && sync && \
install-php-extensions amqp apcu bcmath exif gd grpc imap intl ldap mcrypt opcache pdo_mysql redis sockets uuid xdebug yaml zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENV COMPOSER_ALLOW_SUPERUSER=1