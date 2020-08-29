FROM php:7.4-alpine

RUN apk --update --no-cache add bash \
   && apk add wget \
   && apk add git \
   && apk add php7 \
   && apk add php7-phar \
   && apk add php7-mbstring \
   && apk add php7-iconv \
   && apk add php7-ctype \
   && apk add php7-posix \
   && apk add php7-intl \
   && apk add php7-opcache  \
   && apk add make \
   && apk add $PHPIZE_DEPS \
   && pecl install xdebug

ADD xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

RUN docker-php-ext-enable xdebug

RUN mkdir -p /var/app
RUN adduser --disabled-password app && chown -R app:app /var/app

# Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

USER app
WORKDIR /var/app

EXPOSE 9000

RUN echo "alias ll='ls -l'" >> ~/.bashrc
RUN echo "alias la='ls -la'" >> ~/.bashrc
