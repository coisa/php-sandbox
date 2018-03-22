FROM php:7.2-alpine
MAINTAINER Felipe Sayão Lobato Abreu <contato@felipeabreu.com.br>

EXPOSE 80

ADD ./ /opt/project
WORKDIR /opt/project

COPY ./ /opt/project

# MySQL dependecies
RUN docker-php-ext-install pdo pdo_mysql

# RabbitMQ dependency
RUN docker-php-ext-install bcmath

# Project dependecies
RUN php -r "readfile('https://getcomposer.org/installer');" | php \
    && \
    php composer.phar install \
        --no-dev \
        --prefer-dist \
        --optimize-autoloader \
    && \
    rm -f composer.phar

CMD ["php", "-S", "0.0.0.0:80", "-t", "/opt/project/public"]

# TODO nginx, monit, letsencrypt (renew), user, permissions