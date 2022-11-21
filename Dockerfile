FROM docker.elastic.co/beats/filebeat:7.14.2
WORKDIR /usr/share/filebeat
USER root
RUN chown root filebeat.yml