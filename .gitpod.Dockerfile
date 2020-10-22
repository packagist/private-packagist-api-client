FROM gitpod/workspace-full

USER root

RUN apt-get update
RUN apt-get -y upgrade
RUN apt-get -y install lsb-release
RUN apt-get -y install apt-utils
RUN apt-get -y install python
RUN apt-get install -y libmysqlclient-dev
RUN apt-get -y install nginx
RUN apt-get -y install rsync
RUN apt-get -y install curl
RUN apt-get -y install libnss3-dev
RUN apt-get -y install openssh-client
RUN apt-get -y install mc
RUN apt install -y software-properties-common
RUN apt-get -y install gcc make autoconf libc-dev pkg-config
RUN apt-get -y install libmcrypt-dev
RUN mkdir -p /tmp/pear/cache
RUN apt install -y php-dev
RUN apt install -y php-pear

#Install php-fpm7.3
RUN apt-get update \
    && apt-get install -y nginx curl zip unzip git software-properties-common \
    && add-apt-repository -y ppa:ondrej/php \
    && apt-get update \
    && apt-get install -y php7.3-fpm php7.3-common php7.3-cli php7.3-imagick php7.3-gd php7.3-mysql \
       php7.3-pgsql php7.3-imap php-memcached php7.3-mbstring php7.3-xml php7.3-xmlrpc php7.3-soap php7.3-zip php7.3-curl \
       php7.3-bcmath php7.3-sqlite3 php7.3-apcu php7.3-apcu-bc php7.3-intl php-xdebug php-redis \
    && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
    && mkdir /run/php \
    && chown gitpod:gitpod /run/php \
    && chown -R gitpod:gitpod /etc/php \
    && apt-get remove -y --purge software-properties-common \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && echo "daemon off;" >> /etc/nginx/nginx.conf

COPY .gitpod/php-fpm.conf /etc/php/7.3/fpm/php-fpm.conf
RUN chown -R gitpod:gitpod /etc/php

USER gitpod

COPY .gitpod/nginx.conf /etc/nginx

USER root

RUN chown -R gitpod:gitpod /etc/php
RUN chown -R gitpod:gitpod /etc/nginx
RUN chown -R gitpod:gitpod /home/gitpod/.composer
RUN chown -R gitpod:gitpod /etc/init.d/
RUN rm -f /usr/bin/php
RUN ln -s /usr/bin/php7.3 /usr/bin/php