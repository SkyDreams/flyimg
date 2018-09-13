FROM flyimg/docker-app

ENV TZ=Europe/Amsterdam

RUN rm -rf /var/lib/apt/lists/*

COPY .        /var/www/html

#add www-data + mdkdir var folder
RUN usermod -u 1000 www-data \
    && mkdir -p /var/www/html/var web/uploads/.tmb var/cache/ var/log/ \
    && chown -R www-data:www-data var/  web/uploads/ \
    && chmod 777 -R var/  web/uploads/

RUN composer update

EXPOSE 80

CMD /usr/bin/supervisord
