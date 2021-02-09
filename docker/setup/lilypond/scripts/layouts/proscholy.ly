%nastavení papíru
\paper {
  print-page-number = ##f	
  indent = 0
}

\header {
  tagline = #ff		% ruší řádek s popiskem dole
}

%přizpůsobení vzhledu
% bylo by dobré tohle umístit do samostatného souboru, aby se snáz dalo měnit
\layout {
    \context {
      \Score \remove "Bar_number_engraver"		% ruší čísla taktů na kraji osnovy      
    }
    \context {
      \ChordNames {
        \set chordRootNamer = #(chord-name->german-markup #t)		% Přepíná B na H
        \set majorSevenSymbol = \markup { maj7 }		% mění zobrazení maj akordů
        \override VerticalAxisGroup.nonstaff-relatedstaff-spacing.padding = #0.7		% posunuje akordy výš (defaultně 0.5)
        \set chordChanges = ##t		% když je vícekrát za sebou stejný akord, zobrazí se jen jednou
      }
    }
    \context {
      \Lyrics {
        %\override LyricHyphen.minimum-distance = #1	% vynucení pomlček mezi slabikami
                                                        % docela jsem si zvyknul to nepoužívat
      }
    }
    \context {
      \Staff \RemoveAllEmptyStaves      
    }
}