FROM  centos:c78
COPY  MariaDB.repo /etc/yum.repos.d/MariaDB.repo
RUN   yum install -y mariadb-server mariadb-client
RUN   touch /var/lib/mysql/mysql.sock
COPY  server.cnf /etc/my.cnf.d/
COPY  mariadb.sh /mariadb.sh
RUN   chmod +x /mariadb.sh
VOLUME ["/var/lib/mysql"]
EXPOSE 3306
ENTRYPOINT ["/mariadb.sh"]
