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
               php-xml\
               php-curl\
               poppler-utils\
               apt-utils\
               composer\
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

#Install necessary pyhton modules
RUN pip install Pillow tqdm rarfile python-magic colored PyMuPDF 

#change php value to accept 4G files
RUN sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 4096M/g' /etc/php/8.1/apache2/php.ini && \
    sed -i 's/memory_limit = 128M/memory_limit = -1/g' /etc/php/8.1/apache2/php.ini && \
    sed -i 's/post_max_size = 8M/post_max_size = 4096M/g' /etc/php/8.1/apache2/php.ini && \
    sed -i 's/max_execution_time = 30/max_execution_time = 1200/g' /etc/php/8.1/apache2/php.ini 

#Setup apache
COPY apache-conf/service.conf /etc/apache2/sites-available/000-default.conf
RUN echo "ServerName converter" >> /etc/apache2/apache2.conf
RUN a2enmod rewrite
EXPOSE 80/tcp

#Copy pyhton converter
COPY /sbin/convert_to_cbz.py /opt/convert_to_cbz.py
RUN chmod +x /opt/convert_to_cbz.py

#Create web directory
RUN mkdir -p /var/www/service

#populate it
RUN git clone https://github.com/pitch7900/convert_to_cbz.git /var/www/service
RUN composer install --working-dir=/var/www/service/
RUN rm -rf /var/www/service/.git && \
    rm -rf /var/www/service/.vscode && \
    rm -rf /var/www/service/docker && \
    rm -rf /var/www/service/logs/*

#Change permissions
RUN chown -R www-data:www-data /var/www/service

WORKDIR /var/www/service

#Do this to have a small backup of what we did
COPY Dockerfile /Dockerfile

ENTRYPOINT ["/usr/sbin/apache2ctl"]
CMD ["-D","FOREGROUND"]
