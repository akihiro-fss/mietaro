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
FROM php:7.1-apache


# ###########################################################
# 
# イメージ作成者
# 
# ###########################################################
MAINTAINER akihiro.toda



# ###########################################################
# 
# パッケージ
# 
# ###########################################################
RUN apt-get -y update && apt-get -y upgrade
RUN apt-get -y install vim

RUN docker-php-ext-install pdo_mysql mysqli mbstring

# 日本語設定
# RUN locale-gen ja_JP.UTF-8
# ENV LANG ja_JP.UTF-8 
# ENV LANGUAGE ja_JP:ja
# ENV LC_ALL ja_JP.UTF-8

# タイムゾーン
RUN ln -fs /usr/share/zoneinfo/Asia/Tokyo /etc/localtime

# localhost設定
RUN echo "ServerName localhost" | tee /etc/apache2/conf-available/fqdn.conf
RUN a2enconf fqdn
RUN a2enmod rewrite