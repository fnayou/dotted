# See https://github.com/docker-library/php/blob/4677ca134fe48d20c820a19becb99198824d78e3/7.0/fpm/Dockerfile
FROM php:7.4-fpm

LABEL maintainer="fnayou.aymen@gmail.com"

# Global dependencies
RUN apt-get update && apt-get install -y  \
    git \
    zip \
    unzip

# configure php.ini
RUN echo "memory_limit = -1;" >> /usr/local/etc/php/conf.d/php.ini

# composer
RUN echo "$(curl -sS https://composer.github.io/installer.sig) -" > composer-setup.php.sig \
    && curl -sS https://getcomposer.org/installer | tee composer-setup.php | sha384sum -c composer-setup.php.sig \
    && php composer-setup.php && rm composer-setup.php* \
    && chmod +x composer.phar && mv composer.phar /usr/bin/composer

# timezone
RUN rm /etc/localtime && ln -s /usr/share/zoneinfo/Europe/Paris /etc/localtime && "date"

RUN usermod -u 1000 www-data

#to avoid conflict, check the nginx configuration
RUN sed -i 's/9000/8000/' /usr/local/etc/php-fpm.d/zz-docker.conf

# hide X-Powered-By: PHP
RUN echo "expose_php = Off" >> /usr/local/etc/php/conf.d/php.ini

CMD ["php-fpm", "-F"]
