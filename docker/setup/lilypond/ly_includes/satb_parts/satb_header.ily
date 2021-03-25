\include "vocal-tkit.ly"
\include "piano-tkit.ly"
\include "addNoteSmall.ly"
\include "vynech.ly"

#(define satb-voice-prefixes
   ;; These define the permitted prefixes to various names.
   ;; They are combined with a fixed set of postfixes to form
   ;; names such as AltoMusic, BassInstrumentName, etc.
   ;; These names may be redefined.
   '("alt"
     "bas"
     "muzi"
     "MenDivided"
     "Piano"
     "PianoLH"
     "PianoRH"
     "solo"
     "soloDruhy"
     "sopran"
     "tenor"
     "zeny"
     "WomenDivided"
     "melodie"
     "empty")) %do not use the empty variable

#(define satb-lyrics-postfixes
   ;; These define the permitted postfixes to the names of lyrics.
   ;; They are combined with the prefixes to form names like
   ;; AltoLyrics, etc.
   ;; These names may be redefined or extended.
  '("Text"
    "TextI"
    "TextII"
    "TextIII"
    "TextIV"))

#(define satb-lyrics-variable-names
   ;; These define the names which may be used to specify stanzas
   ;; which go between the two two-voice staves when TwoVoicesPerStaff
   ;; is set to #t.  They may be redefined or extended.
  '("VerseOne"
    "VerseTwo"
    "VerseThree"
    "VerseFour"
    "VerseFive"
    "VerseSix"
    "VerseSeven"
    "VerseEight"
    "VerseNine"))


\layout {
  \context {
    \Staff
    \override VerticalAxisGroup.remove-empty = ##t
    \override VerticalAxisGroup.remove-first = ##t
  }
}
TwoVoicesPerStaff = ##t


#(define-missing-variables! '("totalScoreObject" "partTimeSignature" "lastPartTimeSignature" "endPartTimeSignature") #f)
#(if (not totalScoreObject)
  (set! totalScoreObject #{ {} #}))
