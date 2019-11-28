FROM debian:buster

# Switch to root user
USER root

# Update repositories and install required package to start
RUN apt-get update -y && apt-get upgrade -y && apt dist-upgrade -y

#Apache2
RUN apt-get -y install apache2

# PHP
RUN apt-get -y install php libapache2-mod-php php-xml php-curl php-memcached php-intl php-mysql php-mbstring

# Git
RUN apt-get -y install git

# Zip
RUN apt-get -y install zip

# Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === 'a5c698ffe4b8e849a443b120cd5ba38043260d5c4023dbf93e1558871f1f07f58274fc6f4c93bcfd858c6bd0775cd8d1') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /bin/composer

# APPLICATION SYMFONY
RUN mkdir /var/www/symfony

# Scripts
COPY scripts/* /bin/ 
RUN chmod +x /bin/symfony-start

# VIRTUAL HOSTS
COPY virtual/* /etc/apache2/sites-available
RUN a2ensite symfony
RUN a2dissite 000-default

# Clean
RUN apt-get autoremove -y \
    && apt-get autoclean -y 

# RUN APPLICATION
CMD symfony-start; apachectl -D FOREGROUND