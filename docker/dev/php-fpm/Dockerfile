FROM php:8.2-fpm-alpine

# ставим необходимые для нормальной работы модули
COPY ./php.ini /usr/local/etc/php/conf.d/php-custom.ini

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN apk add git
RUN apk add --no-cache zip libzip-dev
RUN docker-php-ext-configure zip
RUN apk add --no-cache autoconf g++ make \
        postgresql-dev
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql
RUN docker-php-ext-install pdo_pgsql

RUN apk add --no-cache pcre-dev $PHPIZE_DEPS \
&& pecl install redis \
&& docker-php-ext-enable redis.so

WORKDIR /data

VOLUME /data

CMD ["php-fpm"]
