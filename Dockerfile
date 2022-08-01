FROM php:8.1-alpine
RUN apk add composer libxml2 libxml2-dev $PHPIZE_DEPS && docker-php-ext-install dom ctype && \
    docker-php-ext-enable dom ctype
ENTRYPOINT ["/usr/bin/composer"]
ADD . /code
WORKDIR /code
CMD ["test"]
