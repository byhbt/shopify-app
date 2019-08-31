FROM php:7.2-fpm-alpine

WORKDIR /app

COPY . .

RUN apk add --update postgresql-dev freetype-dev libpng-dev libjpeg-turbo-dev freetype libpng libjpeg-turbo git\
 && docker-php-ext-configure gd \
    --with-gd \
    --with-freetype-dir=/usr/include/ \
    --with-png-dir=/usr/include/ \
    --with-jpeg-dir=/usr/include/ \
 && docker-php-ext-install gd pdo pdo_pgsql pgsql opcache zip \
 && apk del --no-cache freetype-dev libpng-dev libjpeg-turbo-dev

RUN apk add --update --virtual build_deps gcc g++ autoconf make &&\
  docker-php-source extract &&\
  pecl install xdebug &&\
  echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini &&\
  echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/xdebug.ini && \
  echo "xdebug.remote_autostart=0" >> /usr/local/etc/php/conf.d/xdebug.ini &&\
  echo "xdebug.remote_handler=dbgp" >> /usr/local/etc/php/conf.d/xdebug.ini &&\
  echo "xdebug.idekey=docker" >> /usr/local/etc/php/conf.d/xdebug.ini &&\
  echo "xdebug.remote_host=192.168.1.15" >> /usr/local/etc/php/conf.d/xdebug.ini &&\
  echo "xdebug.remote_port=9000" >> /usr/local/etc/php/conf.d/xdebug.ini &&\
  echo "xdebug.remote_connect_back=1" >> /usr/local/etc/php/conf.d/xdebug.ini &&\
  docker-php-ext-enable xdebug &&\
  docker-php-source delete && \
  apk del build_deps && \
  rm -rf /var/cache/apk/* && \
  rm -rf /tmp/*
