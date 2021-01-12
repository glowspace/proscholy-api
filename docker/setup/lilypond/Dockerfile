FROM ubuntu:bionic

# RUN locale-gen en_US.UTF-8 && DEBIAN_FRONTEND=noninteractive dpkg-reconfigure locales
# ENV LANG en_US.UTF-8
# ENV LANGUAGE en_US:en
# ENV LC_ALL en_US.UTF-8

# install the lilypond package

RUN apt-get update
RUN apt-get -y install bzip2 unzip wget

RUN wget https://lilypond.org/download/binaries/linux-64/lilypond-2.22.0-1.linux-64.sh
RUN sh lilypond-2.22.0-1.linux-64.sh && rm lilypond-2.22.0-1.linux-64.sh
# bzip2


RUN wget https://github.com/msoap/shell2http/releases/download/1.13/shell2http-1.13.linux.amd64.tar.gz && \
    tar -xf shell2http-1.13.linux.amd64.tar.gz

COPY paper_fix.txt .

# run a simple http server that listens on /svg route for a POST request with a multipart form data:
# file_lilypond -> contents of lilypond formatted file

ENTRYPOINT ./shell2http -form \
    POST:/svg_a0 'cat $filepath_file_lilypond paper_fix.txt | (lilypond --output=score -dbackend=svg --format=svg -dno-point-and-click -djob-count=4 - 2>score.log && cat score.svg && rm score.svg) || cat score.log'