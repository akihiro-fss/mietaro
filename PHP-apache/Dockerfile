# ###########################################################
# 
# 作成日 2019/12/08     作成者 akihiro.toda
# 更新日 2020/02/17     更新者 akihiro.toda
# 
# Copyright (c) Flugel System Studio - All rights reserved.
# ###########################################################


# ###########################################################
# 
# ベースイメージを指定
# 
# ###########################################################
<<<<<<< HEAD:docker/PHP/Dockerfile
# FROM centOS:7.5.1804
FROM centOS:7.5
=======
FROM php:7.1-apache
>>>>>>> master:PHP-apache/Dockerfile


# ###########################################################
# 
# イメージ作成者
# 
# ###########################################################
MAINTAINER akihiro.toda


RUN apt-get -y update && apt-get -y upgrade
RUN apt-get -y install vim

<<<<<<< HEAD:docker/PHP/Dockerfile
# ########################################################### 
# 
# centosパッケージ取得
# 
# ###########################################################
RUN yum -y clean && update && upgrade
RUN yum -y install epel-release
RUN yum -y install http://rpms.famillecollet.com/enterprise/remi-release-7.rpm
RUN yum -y install php71 php71-php
RUN yum -y install httpd
RUN yum -y install vim

RUN systemctl enable httpd.service
=======
RUN docker-php-ext-install pdo_mysql mysqli mbstring

CMD echo "ServerName localhost" >> /etc/apache2/apache2.conf
>>>>>>> master:PHP-apache/Dockerfile
