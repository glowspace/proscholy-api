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


# lilypond --format=ps a.ly (with paper setup)
# gs -sDEVICE=bbox -q -dBATCH -dNOPAUSE a.ps

# convert to svg bbox (x2, y2 to width, height)
# test..?

# lilypond -dbackend=svg --format=svg -dno-point-and-click -djob-count=4 a.ly


# ----------------------------------------------------
# found solution
# ly > eps, get bbox = (a,b,c,d), svg_viewbox = (a, -d, c-a, d-b)/5, add some units to height and width, remove svg width and height