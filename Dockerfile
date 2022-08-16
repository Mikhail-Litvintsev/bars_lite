FROM php:8.1.9-fpm-alpine
WORKDIR /var/www/html

RUN set -ex \
  && apk --no-cache add \
    postgresql-dev

RUN apk add --no-cache zip libzip-dev nodejs npm supervisor
#RUN docker-php-ext-install zip
#RUN docker-php-ext-install exif
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_pgsql

RUN apk update
#RUN apk add --no-cache zip zlib-dev
#RUN apk add --no-cache \
#      freetype \
#      libjpeg-turbo \
#      libpng \
#      freetype-dev \
#      libjpeg-turbo-dev \
#      libpng-dev \
#    && docker-php-ext-configure gd \
#      --with-freetype=/usr/include/ \
#      # --with-png=/usr/include/ \ # No longer necessary as of 7.4; https://github.com/docker-library/php/pull/910#issuecomment-559383597
#      --with-jpeg=/usr/include/ \
#    && docker-php-ext-install -j$(nproc) gd \
#    && docker-php-ext-enable gd \
#    && apk del --no-cache \
#      freetype-dev \
#      libjpeg-turbo-dev \
#      libpng-dev \
#    && rm -rf /tmp/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
#RUN echo "memory_limit = 1024M" > /usr/local/etc/php/conf.d/memory.ini

# Give execution rights on the cron job
#COPY crontab /etc/crontabs/root
#RUN chmod 0644 /etc/cron.d/cron
#RUN crontab /etc/cron.d/cron
# Create the log file to be able to run tail
#RUN touch /var/log/cron.log

#Install Cron
#RUN apk add crond
#RUN apk add --upgrade apk-cron
# Run the command on container startup
#CMD crond && tail -f /var/log/cron.log
#CMD ["crond", "-f"]
# Change current user to www
USER root

CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]