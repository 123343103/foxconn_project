FROM centos
ENV MYSQL_VERSION 8.0.12 
RUN yum -y install gcc gcc-c++ wget cmake make ncurses-devel openssl-devel \
    && cd /usr/local/src \
    && wget https://dev.mysql.com/get/Downloads/MySQL-8.0/mysql-boost-${MYSQL_VERSION}.tar.gz \
    && tar -zxf mysql-boost-${MYSQL_VERSION}.tar.gz \
    && cd /usr/local/src/mysql-$MYSQL_VERSION \
    && cmake -DCMAKE_INSTALL_PREFIX=/usr/local/mysql \
	   -DWITH_BOOST=boost \
	   -DMYSQL_DATADIR=/usr/local/mysql/data \
	   -DSYSCONFDIR=/usr/local/mysql/etc \
    && make \  
    && make install \
    && groupadd mysql \
    && useradd -r -g mysql -s /bin/false mysql \
    && cd /usr/local/mysql \
    && bin/mysqld --initialize-insecure --user=mysql \
    && yum clean all
#COPY my.cnf /usr/local/mysql/etc/my.cnf
EXPOSE 3306
CMD ["/usr/local/mysql/bin/mysqld_safe"]
