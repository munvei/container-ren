FROM centos:c78
RUN yum update -y
RUN yum install -y httpd iproute epel-release
RUN yum install -y http://rpms.famillecollet.com/enterprise/remi-release-7.rpm
RUN yum update -y
RUN systemctl enable httpd
RUN yum install -y --enablerepo=epel,remi,remi-php74 php php-devel php-mbstring php-pdo php-mysql php-gd php-xml php-mcrypt php-fpm
RUN rm -f /etc/httpd/conf.d/welcome.conf
