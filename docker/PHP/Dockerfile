# ###########################################################
# 
# 作成日 2019/12/08
# 更新日
# 
# Copyright (c) Flugel System Studio - All rights reserved.
# ###########################################################

# ###########################################################
# 
# ベースイメージを指定
# 
# ###########################################################
# FROM centOS:7.5.1804
FROM centOS:7.5


# ###########################################################
# 
# イメージ作成者
# 
# ###########################################################
MAINTAINER akihiro.toda



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