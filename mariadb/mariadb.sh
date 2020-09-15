#!/bin/bash
SQLFILE=/tmp/mariadb.sql
cat << EOF >> $SQLFILE
CREATE DATABASE $DBNAME DEFAULT CHARACTER SET utf8;
CREATE TABLE $DBNAME.accounts (id char(14) not null, name varchar(32) not null, pw varchar(64) not null, primary key (id));
CREATE USER '$MARIADBUSER'@'localhost' IDENTIFIED BY '$MARIADBPASSWORD';
GRANT ALL PRIVILEGES ON $DBNAME.* TO '$MARIADBUSER'@'localhost' WITH GRANT OPTION;
CREATE USER '$MARIADBUSER'@'%'         IDENTIFIED BY '$MARIADBPASSWORD';
GRANT ALL PRIVILEGES ON       *.* TO '$MARIADBUSER'@'%'         WITH GRANT OPTION;
FLUSH PRIVILEGES;
EOF

mysql_install_db
mkdir -p /var/run/mysql /var/lib/mysql /var/log/mysql
chown -R mysql:mysql /var/run/mysql /var/lib/mysql /var/log/mysql
/usr/bin/mysqld_safe --datadir='/var/lib/mysql' &
sleep 20
# mysqladmin -u $MARIADBUSER password $MARIADBPASSWORD
# mysql -u $MARIADBUSER -p$MARIADBPASSWORD < $SQLFILE
mysql -uroot < $SQLFILE
rm -rf $SQLFILE

tail -f /dev/null
