FROM composer:2 AS composer-build
WORKDIR /code
COPY composer.* ./
RUN composer install --ignore-platform-reqs --no-ansi --no-scripts --no-progress -o -n -d /code/

FROM php:8.0-fpm
HEALTHCHECK --start-period=10s --interval=20s --timeout=3s CMD bash -c '[[ "`curl --silent --fail http://localhost/healthcheck`" == "ok" ]] || exit 1'
RUN docker-php-ext-configure pcntl --enable-pcntl && docker-php-ext-install pcntl
COPY --from=composer-build /code /code
COPY . /code
WORKDIR /code
RUN cp docker/php.ini /usr/local/etc/php/conf.d/zzz.ini;
RUN mkdir -p /code/var && chmod -R 0777 /code/var && echo "$(TZ="UTC" date '+%Y-%m-%d %H:%M:%S')" > /code/var/build.txt

CMD ["/code/docker/entrypoint.sh"]
