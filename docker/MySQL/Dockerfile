# #######################################
# 
# 
# 
# 
# 
# #######################################

FROM mysql:5.7

MAINTAINER akihiro.toda

RUN apt-get clean && apt-get update && apt-get upgrade -y

RUN sed -i.bak -e "s%http://archive.ubuntu.com/ubuntu/%http://ftp.jaist.ac.jp/pub/Linux/ubuntu/%g" /etc/apt/source.list

ENV TZ Asia/Tokyo
RUN apt-get update ¥
    && apt-get install -y tzdata ¥
    && rm -rf /var/lib/apt/list/ * ¥
    && echo "${TZ}" > /etc/timezone ¥
    && rm /etc/localtime ¥
    && ln -s /usr/share/zoneinfo/Asia/Tokyo /etc/localtime ¥
    && dpkg-reconfigure -f noninteractive tzdata




