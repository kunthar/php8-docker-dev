FROM phpdockerio/php80-fpm:latest
WORKDIR "/application"

RUN apt-get update; \
    apt-get -y --no-install-recommends install \
        git \ 
        php8.0-bcmath \ 
        php8.0-bz2 \ 
        php8.0-decimal \ 
        php8.0-ds \ 
        php8.0-enchant \ 
        php8.0-gd \ 
        php8.0-gmp \ 
        php8.0-igbinary \ 
        php8.0-imagick \ 
        php8.0-imap \ 
        php8.0-intl \ 
        php8.0-ldap \ 
        php8.0-lz4 \ 
        php8.0-mailparse \ 
        php8.0-maxminddb \ 
        php8.0-mcrypt \ 
        php8.0-mysql \ 
        php8.0-oauth \ 
        php8.0-odbc \ 
        php8.0-pgsql \ 
        php8.0-redis \ 
        php8.0-soap \ 
        php8.0-sqlite3 \ 
        php8.0-ssh2 \ 
        php8.0-uuid \ 
        php8.0-xdebug \ 
        php8.0-xmlrpc \ 
        php8.0-xsl \ 
        php8.0-yaml \ 
        php8.0-zstd; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
