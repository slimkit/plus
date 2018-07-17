FROM php:7.2-fpm

# install the PHP extensions we need
RUN set -ex; \
	pecl channel-update pecl.php.net; \
	\
	savedAptMark="$(apt-mark showmanual)"; \
	\
	apt-get update; \
	apt-get install -y --no-install-recommends \
		libfreetype6-dev \
		libjpeg62-turbo-dev \
	    libmcrypt-dev \
	    libpng-dev \
	    libxml2-dev \
	    libmemcached-dev \
	    zlib1g-dev \
	; \
	docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/; \
	docker-php-ext-install -j$(nproc) iconv gd; \
	docker-php-ext-install \
		soap \
		pdo_mysql \
		zip \
		mbstring \
		opcache \
	; \
	pecl install channel://pecl.php.net/mcrypt-1.0.1; \
	pecl install memcached; \
	docker-php-ext-enable mcrypt; \
	docker-php-ext-enable memcached; \
# reset apt-mark's "manual" list so that "purge --auto-remove" will remove all build dependencies
	apt-mark auto '.*' > /dev/null; \
	apt-mark manual $savedAptMark; \
	ldd "$(php -r 'echo ini_get("extension_dir");')"/*.so \
		| awk '/=>/ { print $3 }' \
		| sort -u \
		| xargs -r dpkg-query -S \
		| cut -d: -f1 \
		| sort -u \
		| xargs -rt apt-mark manual; \
	\
	apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false; \
	rm -rf /var/lib/apt/lists/*

# set recommended PHP.ini settings
# see https://secure.php.net/manual/en/opcache.installation.php
RUN { \
		echo 'opcache.memory_consumption=128'; \
		echo 'opcache.interned_strings_buffer=8'; \
		echo 'opcache.max_accelerated_files=4000'; \
		echo 'opcache.revalidate_freq=2'; \
		echo 'opcache.fast_shutdown=1'; \
		echo 'opcache.enable_cli=1'; \
	} > /usr/local/etc/php/conf.d/opcache-recommended.ini

# Install Composer
# see https://getcomposer.org/download/
RUN curl -s http://getcomposer.org/installer | php; \
	echo "export PATH=${PATH}:/var/www/vendor/bin" >> ~/.bashrc; \
	mv composer.phar /usr/local/bin/composer

## Load Plus(ThinkSNS+)
COPY . /usr/src/plus
RUN set -ex; \
	rm -rf /var/www; \
	mkdir /var/www; \
	chown -R www-data:www-data /usr/src/plus; \
	chown -R www-data:www-data /var/www
USER www-data
RUN set -ex; \
	composer install \
		--no-dev \
		--optimize-autoloader \
		--classmap-authoritative \
		--ignore-platform-reqs \
		--working-dir=/usr/src/plus
USER root
COPY docker-entrypoint.sh /usr/local/bin/
RUN set -ex; \
	chmod -f +x /usr/local/bin/docker-entrypoint.sh
ENTRYPOINT ["docker-entrypoint.sh"]

WORKDIR /var/www
VOLUME /var/www

CMD ["php-fpm"]
