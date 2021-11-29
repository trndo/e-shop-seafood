FROM php:7.3-fpm

ARG environment=prod
ARG COMPOSER_AUTH

RUN rm -f /usr/local/etc/www.conf.default && rm -f /usr/local/etc/docker.conf && rm -f /usr/local/etc/zz-docker.conf

RUN if [ $environment != "prod" ]; then pecl install xdebug && docker-php-ext-enable xdebug; fi

RUN apt-get update && apt-get install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip \
    && docker-php-ext-install intl opcache pdo pdo_mysql \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

#RUN mkdir ~/.ssh
#RUN echo 'Host github.com\n\
#            StrictHostKeyChecking no\n\
#            IdentityFile ~/.ssh-local/id_rsa' > ~/.ssh/config

COPY docker/fpm/config/php_dev.ini /usr/local/etc/php/php.ini
COPY docker/fpm/config/php-fpm.conf /usr/local/etc/php-fpm.conf
COPY docker/fpm/config/www_dev.conf /usr/local/etc/php-fpm.d/www.conf

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=1.8.6

####### Symfony cli
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony
#######

WORKDIR /usr/src/app
COPY . /usr/src/app

#RUN if [ $environment = "prod" ]; then APP_ENV=prod composer install --no-interaction \
#       && echo "Fix permissions" \
#       && chown -R www-data:www-data /usr/src/app; \
#     else \
#       composer install --no-interaction; fi


RUN apt-get autoremove -y && apt-get autoclean
RUN rm -rf /var/lib/apt/lists/*

CMD ["php-fpm"]