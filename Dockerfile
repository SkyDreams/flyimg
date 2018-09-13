FROM flyimg/docker-app

ENV TZ=Europe/Amsterdam

# Setup cronjob for removing tmp files
RUN apt-get update && apt-get install -y \
    cron \
 && rm -rf /var/lib/apt/lists/*
COPY ./remove-tmp-files.sh /var/www/html/remove-tmp-files.sh
# setup Supervisor to start the cron
COPY ./supervisord.conf /etc/supervisor/conf.d/supervisord.conf

COPY ./ /var/www/html

#add www-data + mkdir var folder + add user crontab
RUN usermod -u 1000 www-data \
    && crontab -u www-data crontab \
    && mkdir -p /var/www/html/var web/uploads/.tmb var/cache/ var/log/ \
    && chown -R www-data:www-data var/  web/uploads/ \
    && chmod 777 -R var/  web/uploads/

RUN composer update

EXPOSE 80

CMD /usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf
