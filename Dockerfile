FROM composer

EXPOSE 80

ADD ./ /opt/project
WORKDIR /opt/project

RUN composer install \
    --no-dev \
    --ignore-platform-reqs \
    --prefer-dist \
    --optimize-autoloader

CMD ["php", "-S", "0.0.0.0:80", "-t", "/opt/project/public"]