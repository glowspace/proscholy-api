FROM docker.elastic.co/elasticsearch/elasticsearch:7.10.1
COPY elasticsearch.yml /usr/share/elasticsearch/config/
# install the czech hunspell dict
COPY hunspell /usr/share/elasticsearch/config/hunspell/
USER root
RUN chown -R elasticsearch:elasticsearch config/*
RUN chown -R elasticsearch:elasticsearch /usr/share/elasticsearch/data
USER elasticsearch
RUN elasticsearch-plugin install analysis-icu