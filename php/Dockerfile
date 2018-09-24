FROM centos
ENV PHP_VERSION 5.6.38
RUN yum -y install wget gcc libxml2-devel libcurl-devel gd-devel openssl-devel zlib-devel make git zip unzip \
    && cd /usr/local/src \
    && wget http://cn2.php.net/get/php-${PHP_VERSION}.tar.gz/from/this/mirror \
    && tar -zxf mirror \
    && cd /usr/local/src/php-$PHP_VERSION \
    && ./configure --prefix=/usr/local/php \
		--enable-fpm \
		--enable-mysqlnd \
		--with-mysqli=mysqlnd \
		--with-pdo-mysql=mysqlnd \
		--with-curl \
		--enable-mbstring \
		--enable-ftp \
		--with-openssl \
		--enable-soap \
		--enable-opcache \
  		--with-zlib \
		--enable-zip \
		--with-gd \
		--with-jpeg-dir \
		--with-png-dir \
		--with-freetype-dir \
    && make \  
    && make install \
#    && export PATH=$PATH:/usr/local/php/bin \
    && yum clean all
COPY php-fpm.conf /usr/local/php/etc/php-fpm.conf
COPY www.conf /usr/local/php/etc/php-fpm.d/www.conf
COPY php.ini /usr/local/php/lib/php.ini
COPY composer /usr/local/bin
EXPOSE 9000
CMD ["/usr/local/php/sbin/php-fpm","-F"]
