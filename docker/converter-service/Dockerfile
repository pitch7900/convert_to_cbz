FROM ubuntu:22.04
LABEL version="1.0"
LABEL description="CBZ Converter"
LABEL os="Ubuntu"

ENV TZ=Europe/Paris
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get -y update
RUN apt-get -y upgrade
RUN apt-get -y install \
               apache2\
               php\
               php-cli\
               poppler-utils\
               apt-utils\
               gzip\
               python3\
               python3-pip\
               imagemagick\
               iputils-ping\
               libmagickcore-dev\
               libmagickwand-dev\
               libmagic1\
               net-tools\
               rar\
               re2c\
               sed\
               sudo\
               tar\
               unrar\
               unzip\
               zip


RUN pip install Pillow tqdm rarfile python-magic colored PyMuPDF 

RUN sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 4096M/g' /etc/php/8.1/apache2/php.ini && \
    sed -i 's/memory_limit = 128M/memory_limit = -1/g' /etc/php/8.1/apache2/php.ini && \
    sed -i 's/post_max_size = 8M/post_max_size = 4096M/g' /etc/php/8.1/apache2/php.ini && \
    sed -i 's/max_execution_time = 30/max_execution_time = 1200/g' /etc/php/8.1/apache2/php.ini 
RUN curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
RUN php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer

COPY apache-conf/service.conf /etc/apache2/sites-available/000-default.conf
RUN echo "ServerName converter" >> /etc/apache2/apache2.conf
RUN a2enmod rewrite
EXPOSE 80/tcp

COPY /sbin/convert_to_cbz.py /opt/convert_to_cbz.py
RUN chmod +x /opt/convert_to_cbz.py
RUN mkdir -p /var/www/service/logs


RUN chown -R www-data:www-data /var/www/service

WORKDIR /var/www/service

ENTRYPOINT ["/usr/sbin/apache2ctl"]
CMD ["-D","FOREGROUND"]
