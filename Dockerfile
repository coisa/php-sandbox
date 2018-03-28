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

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

# Project dependecies
RUN composer install \
        --no-dev \
        --prefer-dist \
        --optimize-autoloader

# Container Command
CMD ["php", "-S", "0.0.0.0:80", "-t", "/opt/project/public"]

# TODO nginx, monit, letsencrypt (renew), user, permissions, CaddyFile