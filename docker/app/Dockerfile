FROM php:5.6-apache

RUN apt-get update && apt-get install -y \
    		libicu-dev \
    		zlib1g-dev \
    &&  docker-php-ext-install zip pdo_mysql intl \
    && rm -rf /var/lib/apt/lists/*

COPY apache.conf /etc/apache2/sites-available/club-app.conf
COPY php.ini /usr/local/etc/php/php.ini
ADD  https://getcomposer.org/composer.phar /usr/local/bin/composer

RUN 	a2enmod rewrite \
	&& 	a2dissite 000-default \
	&& 	a2ensite club-app \
	&&  mkdir -p /var/log/club-app \
	&&  chown -R www-data:www-data /var/log/club-app \
	&&  mkdir -p /var/cache/club-app \
	&&  chown -R www-data:www-data /var/cache/club-app \
	&&  chmod +x /usr/local/bin/composer

WORKDIR /var/www/club-app
