# libwoff-dev has only a bionic package
FROM ubuntu:bionic

# RUN locale-gen en_US.UTF-8 && DEBIAN_FRONTEND=noninteractive dpkg-reconfigure locales
# ENV LANG en_US.UTF-8
# ENV LANGUAGE en_US:en
# ENV LC_ALL en_US.UTF-8

# install the lilypond package

RUN apt-get update
RUN apt-get -y install bzip2 
RUN apt-get update
RUN apt-get -y install wget

RUN wget https://lilypond.org/download/binaries/linux-64/lilypond-2.22.0-1.linux-64.sh
RUN sh lilypond-2.22.0-1.linux-64.sh && rm lilypond-2.22.0-1.linux-64.sh
# bzip2

RUN apt-get -y install ghostscript

RUN wget https://github.com/msoap/shell2http/releases/download/1.13/shell2http-1.13.linux.amd64.tar.gz && \
    tar -xf shell2http-1.13.linux.amd64.tar.gz

RUN apt-get -y install xmlstarlet
RUN apt-get -y install python3
RUN apt-get -y install make
RUN apt-get -y install tree

# todo: move this logic to python preprocessing step
COPY paper_fix.txt .

COPY scripts/* /bin/scripts/
RUN chmod +x /bin/scripts/*

COPY fonts/*.otf /usr/local/lilypond/usr/share/lilypond/current/fonts/otf/



WORKDIR /lilywork

RUN adduser --disabled-password --gecos '' lilyponder && \
    chown -R lilyponder /lilywork 

USER lilyponder

ENTRYPOINT /shell2http -form \
    POST:/make 'TMPDIR=`mktemp XXXX -d` && cd $TMPDIR && ln -s /bin/scripts/Makefile && cat $filepath_file_lilypond /paper_fix.txt > score.ly && make $v_recipe > /dev/null; rm Makefile; cd ..; tree $TMPDIR -J -L 1 --noreport' \
    GET:/get 'cd $v_dir && cat $v_file' \
    GET:/del 'rm -r $v_dir && echo "ok" || echo "not deleted"'
# POST:/make/svg 'TMPDIR=`mktemp XXXX -d` && cat $filepath_file_lilypond paper_fix.txt | (lilypond --output=$TMPFILE -dbackend=svg --format=svg -dno-point-and-click -djob-count=4 - 2>$TMPFILE.log && cat $TMPFILE.svg && rm $TMPFILE.svg) || cat $TMPFILE.log'




# # needed to build the 'dvisvgm' package
# RUN apt-get -y install gcc g++ libkpathsea-dev

# RUN wget https://github.com/mgieseki/dvisvgm/releases/download/2.11/dvisvgm-2.11.tar.gz
# RUN tar -xf dvisvgm-2.11.tar.gz

# RUN apt-get install -y pkg-config libfreetype6-dev libbrotli-dev

# RUN apt-get -y install libwoff-dev

# # todo: make (install) dvisvgm, cd dvisgm, ./configure, make, (make install)
# # but the build process takes quite long :/

# # RUN wget https://github.com/scottkosty/install-tl-ubuntu/raw/master/install-tl-ubuntu && chmod +x ./install-tl-ubuntu

# # # run a simple http server that listens on /svg route for a POST request with a multipart form data:
# # # file_lilypond -> contents of lilypond formatted file

# # ENTRYPOINT ./shell2http -form \
# #     POST:/svg 'cat $filepath_file_lilypond > a.ly && /bin/make_svg.sh'

# # install TeXLive
# RUN apt-get -y install software-properties-common

# RUN add-apt-repository ppa:jonathonf/texlive-2019
# RUN apt-get update
# RUN apt-get -y install texlive