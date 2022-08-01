memcached:
	docker-compose up socket net -d

test: memcached
	docker-compose up --build test

junit: memcached
	docker-compose up --build junit

clean:
	docker-compose stop && \
	docker-compose rm -fv
