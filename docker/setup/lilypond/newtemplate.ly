\version "2.22.0"

% http://lilypond.org/doc/v2.22/Documentation/snippets/templates#templates-satb-choir-template-_002d-four-staves

melodie = {  
  \partial 4. { e8 g a | } b c b ~ b4. |
  r b8 a g | a b a ~ a4. |
  r a8 g fis | g fis g ~ g4. |
  r g8 e g | fis4. r |
  r e8 e fis
 | g4. ~ g |
  r g8 fis e | c4. c |
  r c8 c d | e4. ~ e4 (f8) |
  e4 r8 e e c | b2. | R2. \bar "|."
}

druhy = {    
  \partial 4. { c8 g a | } b c b ~ b4. |
  r b8 a g | a b a ~ a4. |
  r a8 g fis | g fis g ~ g4. |
  r g8 e g | fis4. r |
  r e8 e fis | g4. ~ g |
  r g8 fis e | c4. c |
  r c8 c d | e4. ~ e4 (f8) |
  e4 r8 e e c | b2. | R2. \bar "|."
}


text = \lyricmode {
  Mé dla -- ně zved -- nu -- té při -- jmi teď, Bo -- že můj, 
  tak ja -- ko ve -- čer -- ní o -- bět -- ní dar.
  Mod -- lit -- ba má ať "k to" -- bě stou -- pá 
  tak, ja -- ko vů -- ně ka -- did -- lo -- vá.
}

akordy = \chordmode {
  s4. es1.:m a:m c b:7
  e:m a:m c b2.:7 b:7
}



font = #"amiri"
showChords = ##t
tonina = g                  
defaultniTonina = g

% --------------------------

melodie = \relative c' {
	\key \defaultniTonina \major
  \time 6/8    
  #(if druhy #{ { \voiceOne } #}) 
	\melodie
}

druhy = \relative c' {
  \voiceTwo
  #(if druhy #{ { \druhy } #})
}

text = {
  \override LyricText #'font-name = \font
	\text
}

akordy = {
	\override ChordName #'font-name = \font
  \akordy
}

\score { \transpose \defaultniTonina \tonina
  <<
    \new ChordNames { #(if showChords #{ { \akordy } #}) }
		\new Staff \with {
      instrumentName = "S, A"
      \consists "Merge_rests_engraver"
    } {
				<<
				 	\new Voice = "one" {  \melodie }
          \new Voice = "two" {  \druhy }
				>>
		 }
    \new Lyrics \lyricsto "one" \text
  >>
}