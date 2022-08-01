FROM php:8.1-alpine
RUN apk add libxml2 libxml2-dev $PHPIZE_DEPS && docker-php-ext-install dom ctype xml xmlwriter && \
    docker-php-ext-enable dom ctype xml xmlwriter
RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer
ENTRYPOINT ["/usr/local/bin/composer"]
ADD . /code
WORKDIR /code
CMD ["test"]
