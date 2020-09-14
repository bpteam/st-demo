FROM php:7.4.10-cli-alpine3.12 AS BUILD_CONTEXT

RUN apk add --no-cache --virtual .build-deps build-base autoconf php7-pear php7-dev gcc musl-dev make \
 && apk add --no-cache git \
 && pecl install redis && docker-php-ext-enable redis \
 && apk del .build-deps


COPY --from=composer:1.10.13 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . /app

RUN composer install

FROM php:7.4.10-cli-alpine3.12

RUN apk add --no-cache --virtual .build-deps build-base autoconf php7-pear php7-dev gcc musl-dev make \
 && pecl install redis && docker-php-ext-enable redis \
 && apk del .build-deps

COPY --from=BUILD_CONTEXT /app /app

WORKDIR /app

CMD ["php", "-S", "0.0.0.0:80", "-t", "/app", "/app/public/index.php"]