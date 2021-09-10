FROM php:8-fpm-alpine

WORKDIR /var/www/html

RUN set -ex \
        && apk add --update --no-cache --virtual .build-deps \
        ${PHPIZE_DEPS} \
        shadow \
        sqlite-dev

RUN pecl install redis

RUN docker-php-ext-install \
    pdo_sqlite \
    pdo_mysql

RUN docker-php-ext-enable redis

# Clean up
RUN rm -rf /var/cache/apk/* \
    && rm -rf /tmp/*

# Install PHP Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add crontab file in the cron directory
COPY crontab /etc/cron.d/cron

RUN chmod 0644 /etc/cron.d/cron

# Add UID '1000' to www-data
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

RUN chown -R www-data:www-data .

# Change current user to www
USER www-data
