FROM ubuntu:xenial

# RUN locale-gen en_US.UTF-8 && DEBIAN_FRONTEND=noninteractive dpkg-reconfigure locales
# ENV LANG en_US.UTF-8
# ENV LANGUAGE en_US:en
# ENV LC_ALL en_US.UTF-8

# install the lilypond package

RUN apt-get update
RUN apt-get -y install lilypond


# download and install simple http server utility for bash commands
RUN apt-get -y install wget unzip

RUN wget https://github.com/msoap/shell2http/releases/download/1.13/shell2http-1.13.linux.amd64.tar.gz && \
    tar -xf shell2http-1.13.linux.amd64.tar.gz

# run a simple http server that listens on /svg route for a POST request with a multipart form data:
# file_lilypond -> contents of lilypond formatted file

ENTRYPOINT ./shell2http -form \
    POST:/svg 'cat $filepath_file_lilypond > score.ly && (lilypond -dbackend=svg --format=svg score.ly 2>err.txt && cat score.svg || cat err.txt)'