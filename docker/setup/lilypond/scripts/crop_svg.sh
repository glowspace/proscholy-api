#!/bin/bash

SCRIPT=`realpath $0`
SCRIPTPATH=`dirname $SCRIPT`

svgbox=`cat -v $1.eps | grep %%BoundingBox | $SCRIPTPATH/svg_bbox.py`
xmlstarlet ed -N svg='http://www.w3.org/2000/svg'  -d '//svg:svg/@width' -d '//svg:svg/@height' -u '//svg:svg/@viewBox' -v "$svgbox" $1.svg > $1_cropped.svg