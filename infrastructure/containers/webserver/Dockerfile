FROM php:7.1-apache

# Argument parsing
ARG user_name="appmgmt"
ARG user_id=1000
ARG group_name="appmgmt"
ARG group_id=1000

# Enable mod rewrite
RUN a2enmod rewrite

# Create local user and add www-data user to the group to ensure apache has permissions on files
# TODO: This mapping local user to www-data user, a temporary workaround
RUN groupadd -g $group_id $group_name
RUN useradd -u $user_id -g $group_id $user_name
RUN usermod -a -G $group_name www-data

# Copy the .bashrc file
COPY files/.bashrc /root/.bashrc

# Change default apache site
RUN rm -rf /var/www/html
COPY files/000-default.conf /etc/apache2/sites-available/000-default.conf

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('SHA384', 'composer-setup.php') === '669656bab3166a7aff8a7506b8cb2d1c292f042046c5a994c43155c0be6190fa0355160742ab2e1c88d40d5be660b410') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN php -r "unlink('composer-setup.php');"

WORKDIR /var/www
