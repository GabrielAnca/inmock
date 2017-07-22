FROM php:7.1-apache

# Argument parsing
ARG user_name="appmgmt"
ARG user_id=1000
ARG group_name="appmgmt"
ARG group_id=1000

# Create local user and add www-data user to the group to ensure apache has permissions on files
# TODO: This is mapping a local user to www-data, a temporary workaround
RUN addgroup --gid $group_id $group_name
RUN adduser --uid $user_id --gid $group_id $user_name
ENV APACHE_RUN_USER $user_name
ENV APACHE_RUN_GROUP $group_name

# Copy the .bashrc file
COPY files/.bashrc /root/.bashrc
COPY files/.bashrc /home/$user_name/.bashrc

# Copy php.ini
COPY files/php.ini /usr/local/etc/php/php.ini

# Install php-zip
RUN apt-get update && apt-get install -y zlib1g-dev && docker-php-ext-install -j$(nproc) zip

# Install php-xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Enable mod rewrite
RUN a2enmod rewrite

# Change default apache site
RUN rm -rf /var/www/html
COPY files/000-default.conf /etc/apache2/sites-available/000-default.conf

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www
