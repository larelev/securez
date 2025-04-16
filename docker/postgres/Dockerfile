FROM postgres:16-alpine

ENV POSTGRES_DB=demo
ENV POSTGRES_USER=mrdemo
ENV POSTGRES_POSTGRES_PASSWORD=demo

#COPY docker/postgres/init.sql /docker-entrypoint-initdb.d/init.sql

#RUN chown -R postgres:postgres /docker-entrypoint-initdb.d/
COPY docker/postgres/init.sh /tmp/init.sh

RUN chmod 777 /tmp/init.sh
