#   make NAME=<jiné_jméno> <cíle>
NAME=score

SHELL=/bin/bash

# todo: add gzip and svg optimalizations
# todo: add logfiles

# V .PHONY uvádíme „falešné“ cíle, tzn. naše symbolická jména pro cíle, které 
# neprodukují stejně pojmenované soubory jako výsledek své činnosti.
.PHONY: svg eps svgcrop clean
# all: dvi pdf ps
svg: ${NAME}.svg
eps: ${NAME}.eps
svgcrop: ${NAME}_cropped.svg

clean:
# Znak „@“ před příkazem zajistí, že make nebude vypisovat text 
# spouštěného příkazu, což je např. zde nežádoucí, neboť ve výpisu 
# překladu chceme vidět jen naši formátovanou zprávu.
	@echo -e "\n\n### Uklid ###\n\n"
# Znak „-“ před příkazem zajistí, že případné selhání příkazu (např. 
# zde „rm ...“ selže, pokud některý ze zadaných souborů již neexistuje) 
# nezpůsobí zastavení provádění zbytku Makefile (např. zde nám nevadí, 
# že se nepodařilo smazat soubory, které stejně neexistují).
	-rm ${NAME}.{eps,svg}
	-rm ${NAME}_cropped.svg

${NAME}.svg: ${NAME}.ly
	@echo -e "\n\n### SVG ###\n\n"
	lilypond -dbackend=svg -dno-point-and-click -djob-count=4 --format=svg ${NAME}.ly > log.txt 2>&1

${NAME}.eps: ${NAME}.ly
	@echo -e "\n\n### EPS ###\n\n"
	lilypond -dbackend=eps -dno-point-and-click --format=eps ${NAME}.ly > log.txt 2>&1

${NAME}_cropped.svg: ${NAME}.svg ${NAME}.eps
	@echo -e "\n\n### SVG cropped ###\n\n"

	svgbox="$(shell cat -v ${NAME}.eps | grep %%BoundingBox | /bin/scripts/svg_bbox.py)" && \
	xmlstarlet ed -N svg='http://www.w3.org/2000/svg' -d '//svg:svg/@width' -d '//svg:svg/@height' -u '//svg:svg/@viewBox' -v "$$svgbox" ${NAME}.svg > ${NAME}_cropped.svg