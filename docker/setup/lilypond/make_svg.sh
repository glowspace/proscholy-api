#!/bin/sh

if lilypond -dbackend=eps -dno-point-and-click --format=eps a.ly 2>err.txt ; then
    ps2pdf -dEPSCrop a.eps
    pdf2svg a.pdf a.svg # converts all fonts to svg curves
    # gzip -S z a.svg
    cat a.svg
else
    cat err.txt
fi


# lilypond -dbackend=eps -dno-point-and-click --format=eps a.ly 

# lilypond -dbackend=eps -dno-point-and-click -dgs-load-fonts --format=eps a.ly

# cp *.otf /usr/local/lilypond/usr/share/lilypond/current/fonts/otf/