# See https://github.com/docker-library/php/blob/4677ca134fe48d20c820a19becb99198824d78e3/7.0/fpm/Dockerfile
FROM php:7.4-fpm

LABEL maintainer="fnayou.aymen@gmail.com"

# Global dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    # dependencies for PHP extensions
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libicu-dev \
    libpq-dev \
    libxml2-dev \
    libjpeg-dev \
    libonig-dev \
    && pecl install xdebug

# PHP extensions
# mbstring replaced with libonig-dev
RUN docker-php-ext-install \
    opcache \
    iconv \
    mbstring \
    intl \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    gd

# MCrypt
RUN pecl install mcrypt-1.0.3 && \
    docker-php-ext-enable mcrypt

# GD
RUN docker-php-ext-configure gd

# xdebug
RUN docker-php-ext-enable xdebug
RUN { \
        echo "error_reporting = E_ALL"; \
        echo "display_startup_errors = On"; \
        echo "display_errors = On"; \
        echo "xdebug.remote_enable=1"; \
        echo "xdebug.remote_connect_back=1"; \
        echo "xdebug.idekey=\"PHPSTORM\""; \
        echo "xdebug.remote_port=9090"; \
    } >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# install apcu
RUN pecl install apcu && \
    pecl install pecl/apcu_bc-1.0.4 && \
    docker-php-ext-enable apcu

#install Imagemagick & PHP Imagick ext
RUN apt-get install -y libmagickwand-dev --no-install-recommends && \
    rm -rf /var/lib/apt/lists/* && \
    pecl install imagick && \
    docker-php-ext-enable imagick

RUN echo "extension=apc.so;" >> /usr/local/etc/php/conf.d/php.ini

# opcache
RUN { \
        echo 'opcache.memory_consumption=128'; \
        echo 'opcache.interned_strings_buffer=8'; \
        echo 'opcache.max_accelerated_files=4000'; \
        echo 'opcache.revalidate_freq=60'; \
        echo 'opcache.fast_shutdown=1'; \
        echo 'opcache.enable_cli=1'; \
    } > /usr/local/etc/php/conf.d/opcache-recommended.ini
RUN echo "realpath_cache_size = 4096k;" >> /usr/local/etc/php/conf.d/php.ini
RUN echo "realpath_cache_ttl = 7200;" >> /usr/local/etc/php/conf.d/php.ini

# configure php.ini / keep in mind to add client_max_body_size 20M;
RUN echo "post_max_size = 20M;" >> /usr/local/etc/php/conf.d/php.ini
RUN echo "upload_max_filesize = 20M;" >> /usr/local/etc/php/conf.d/php.ini
RUN echo "memory_limit = -1;" >> /usr/local/etc/php/conf.d/php.ini

# composer
RUN echo "$(curl -sS https://composer.github.io/installer.sig) -" > composer-setup.php.sig \
    && curl -sS https://getcomposer.org/installer | tee composer-setup.php | sha384sum -c composer-setup.php.sig \
    && php composer-setup.php && rm composer-setup.php* \
    && chmod +x composer.phar && mv composer.phar /usr/bin/composer

# timezone
RUN rm /etc/localtime && ln -s /usr/share/zoneinfo/Europe/Paris /etc/localtime && "date"

RUN usermod -u 1000 www-data

# to avoid conflict, check the nginx configuration
RUN sed -i 's/9000/9003/' /usr/local/etc/php-fpm.d/zz-docker.conf

# hide X-Powered-By: PHP
RUN echo "expose_php = Off" >> /usr/local/etc/php/conf.d/php.ini

CMD ["php-fpm", "-F"]
