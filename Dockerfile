FROM flyimg/docker-app

ENV TZ=Europe/Amsterdam

COPY .        /var/www/html

#add www-data + mdkdir var folder
RUN usermod -u 1000 www-data \
    && mkdir -p /var/www/html/var web/uploads/.tmb var/cache/ var/log/ \
    && chown -R www-data:www-data var/  web/uploads/ \
    && chmod 777 -R var/  web/uploads/

# Setup cronjob for removing tmp files
RUN apt-get update && apt-get install -y \
    cron \
 && rm -rf /var/lib/apt/lists/*
ADD ./crontab /etc/cron.d/docker
ADD ./remove-tmp-files.sh /var/www/html/remove-tmp-files.sh

EXPOSE 80

CMD /usr/bin/supervisord
