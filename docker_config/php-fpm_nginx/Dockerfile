FROM php:8.1.9-fpm-alpine3.16

RUN apk update && \
    apk upgrade && \
    apk add vim nginx

#タイムゾーン設定
RUN apk add --update --no-cache tzdata && \
    cp /usr/share/zoneinfo/Asia/Tokyo /etc/localtime && \
    apk del tzdata
#    echo "Asia/Tokyo" > /etc/timezone && \

RUN docker-php-ext-install pdo_mysql

RUN apk add autoconf build-base && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"  && \
	php composer-setup.php  && \
	mv composer.phar /usr/local/bin/composer && \
	composer -v

#RUN echo '<?php phpinfo();' > /var/www/localhost/htdocs/index.php
#RUN echo 'clear_env = no' >> /usr/local/etc/php-fpm.d/www.conf

RUN if [ ! -d /run/nginx ] ; then mkdir /run/nginx ; fi
RUN if [ ! -d /run/php ] ; then mkdir /run/php ; fi

#COPY ./zz-www.conf /usr/local/etc/php-fpm.d/zz-www.conf
#COPY ./nginx.conf /etc/nginx/conf.d/nginx.conf

#xdebugを有効にする
RUN echo '#コメントアウト'             > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
	echo '#extension=xdebug'        >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
	echo 'zend_extension=xdebug.so' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo 'xdebug.mode=coverage'     >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

WORKDIR /opt

ENTRYPOINT /bin/sh -c "chown -R www-data:www-data laravel_app/storage && nginx && php-fpm"

RUN apk --no-cache add gettext
