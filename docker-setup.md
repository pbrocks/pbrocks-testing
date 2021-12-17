# setup.md

## Set up plugin

```sh
mkdir pbrocks-dictionary-testing
cd $_
git init
touch .gitignore
touch pbrocks-dictionary-testing.php
git add .
git commit -m "PBrocks first commit"
git branch -m main
```

## Create a Dockerfile

```sh
# Dockerfile
FROM wordpress

ARG PLUGIN_NAME=pbrocks-dictionary-testing

# Setup the OS
RUN apt-get -qq update ; apt-get -y install unzip curl sudo subversion mariadb-client \
	&& apt-get autoclean \
	&& chsh -s /bin/bash www-data

# Install wp-cli
RUN curl https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar > /usr/local/bin/wp-cli.phar \
	&& echo "#!/bin/bash" > /usr/local/bin/wp-cli \
	&& echo "su www-data -c \"/usr/local/bin/wp-cli.phar --path=/var/www/html \$*\"" >> /usr/local/bin/wp-cli \
	&& chmod 755 /usr/local/bin/wp-cli* \
	&& echo "*** wp-cli command installed"

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
	&& php composer-setup.php \
	&& php -r "unlink('composer-setup.php');" \
	&& mv composer.phar /usr/local/bin/ \
	&& echo "#!/bin/bash" > /usr/local/bin/composer \
	&& echo "su www-data -c \"/usr/local/bin/composer.phar --working-dir=/var/www/html/wp-content/plugins/${PLUGIN_NAME} \$*\"" >> /usr/local/bin/composer \
	&& chmod ugo+x /usr/local/bin/composer \
	&& echo "*** composer command installed"
```

## Create a Docker Image

```sh
docker build -t pbrocks-dictionary-testing .
```

## Check Docker

Don't have a database yet

```sh
docker run --rm -p 8383:80 pbrocks-dictionary-testing
```

## Remove Docker Image

```sh
docker image rmi pbrocks-dictionary-testing
```

## Add YAML file

```sh
# docker-compose.yml
version: '3.7'

services:

  # MySQL database
  db:
    image: mariadb
    restart: unless-stopped
    container_name: pbrocks-dictionary
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: wordpress
      MYSQL_USER: wordpress
      MYSQL_PASSWORD: wordpress
    volumes:
      - db-data:/var/lib/mysql

  # Wordpress
  wp:
    build:
      context: .
      dockerfile: Dockerfile
    restart: unless-stopped
    container_name: pbrocks-wp
    environment:
      WORDPRESS_DB_HOST: pbrocks-dictionary
      WORDPRESS_DB_USER: wordpress
      WORDPRESS_DB_PASSWORD: wordpress
      WORDPRESS_DB_NAME: wordpress
      WORDPRESS_DEBUG: 1
    volumes:
      - wp-data:/var/www/html
      - ./:/var/www/html/wp-content/plugins/pbrocks-dictionary-testing
    ports:
      - 8383:80
    depends_on:
      - db

# Make network name pretty
networks:
  default:
    name: pbrocks-nettest

# Persist DB and WordPress data across containers
volumes:
  db-data: {}
  wp-data: {}
```

## Build Docker Container

```sh
docker-compose build wp
docker-compose up -d
```

## Add Composer

```sh
touch composer.json composer.lock
mkdir vendor
chmod 777 vendor composer.json composer.lock
```

## Add to composer.json

```json
{
    "require": {
        "phpunit/phpunit": "^7",
        "yoast/phpunit-polyfills": "^1.0.1"
    },
    "scripts": {
        "phpunit": "phpunit"
    }
}
```
## Add to Dockerfile

```sh
# Create testing environment
COPY --chmod=755 bin/install-wp-tests.sh /usr/local/bin/
RUN echo "#!/bin/bash" > /usr/local/bin/install-wp-tests \
	&& echo "su www-data -c \"install-wp-tests.sh \${WORDPRESS_DB_NAME}_test root root \${WORDPRESS_DB_HOST} latest\"" >> /usr/local/bin/install-wp-tests \
	&& chmod ugo+x /usr/local/bin/install-wp-test* \
	&& su www-data -c "/usr/local/bin/install-wp-tests.sh ${WORDPRESS_DB_NAME}_test root root '' latest true" \
	&& echo "*** install-wp-tests installed"
```

## Run Composer in Container

```sh
docker-compose exec wp composer require phpunit/phpunit=^7
docker-compose exec wp install-wp-tests
```

## Run Unit Tests

```sh
docker-compose exec wp composer phpunit
```
