FROM postgres:15-alpine

RUN mkdir -p /usr/local/share/postgresql/tsearch_data

COPY uk_ua.affix /usr/local/share/postgresql/tsearch_data/uk_ua.affix
COPY uk_ua.dict  /usr/local/share/postgresql/tsearch_data/uk_ua.dict
COPY ukrainian.stop /usr/local/share/postgresql/tsearch_data/ukrainian.stop
