#!/usr/bin/python3
import optparse
import sys
import os

src_dir = os.path.dirname(os.path.realpath(__file__))

def set_paper_size(w, h):
    return "#(set! paper-alist (cons '(\"my size\" . (cons (* {}) (* {}))) paper-alist))".format(w, h) + """
    \\paper {
        #(set-paper-size "my size")
    }"""

#     % hodnoty z frontendu, načítat z "proscholy-web/pages/song/store.js" nebo tak nějak
# tonina = e		% tónina do které se transponuje 
#                         % hodnoty: c cis des d dis es e f fis ges g gis as a b c

# showChords = ##t	% přepínač zobrazení akordů 
#                         % hodnoty: ##f ##t
                        

# %%%%%% k vyplnění v redakci >>>

# defaultniTonina = e	% vyplňuje redaktor, ideálně formou <select>
#                         % hodnoty: c cis des d dis es e f fis ges g gis as a b c


src = sys.stdin.readlines()

if __name__ == '__main__':
    parser = optparse.OptionParser(usage="%prog [OPTIONS]")
    parser.add_option('-t', '--template', default="desktop",
                      help='Choose the paper template (desktop, mobile)')

    parser.add_option('-l', '--layout', default="proscholy",
                        help="Layout file to use (layouts/{}.ly)")

#     # parser.add_option('-g', '--gdexconfig', default="gdex_english_lemmatized",
#     #                   help='GDEX config file')
#     # parser.add_option('-o', '--output',
#     #                   help='Relative path for the output csv file')
#     # parser.add_option('-a', '--attr', default="lemma",
#     #                   help='Concordance search attribute')
#     # parser.add_option('-w', '--word',
#     #                   help='Word (phrase) to search for OR file with list of vocab')
#     # parser.add_option('-n', '--number', default=4000, type='int',
#     #                   help="Number of sentences to extract")
#     # parser.add_option('-t', '--threshold', default=0.6, type='float',
#     #                   help="GDEX threshold")
#     # parser.add_option('-l', '--logfile',
#     #                   help='Relative path for the log file')
    options, args = parser.parse_args()

#     # todo: import lilypond parsing library

    for line in src:
        print(line)

    # if options.layout == 'proscholy':
    #     with open('{}/layouts/{}.ly'.format(src_dir, options.layout), 'r', encoding='utf-8') as layout:
    #         for line in layout.readlines():
    #             print(line)

    if options.template == 'desktop':
        print(set_paper_size('148 mm', '3000 in'))

