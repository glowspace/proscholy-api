% defined in satb_header.ly

#(set-music-definitions!
  satb-voice-prefixes
  satb-lyrics-postfixes
  satb-lyrics-variable-names)

% defined by user

#(if (and (not Time) solo)
      (set! Time #{ \vynech \solo #}))
#(if (and (not Time) sopran)
      (set! Time #{ \vynech \sopran #}))

#(placehold-voices-and-lyrics! Time)

timeNotChanged = #(equal? partTimeSignature lastPartTimeSignature)


% include time_signature when different from lastPartTimeSignature
Time = #(if timeNotChanged Time
  #{ {
      \partTimeSignature
      \Time
    } #})

#(if endPartTimeSignature 
  (set! lastPartTimeSignature endPartTimeSignature) 
  (set! lastPartTimeSignature partTimeSignature))



#(if (and soloDruhy solo)
      (set! solo #{ \addNoteSmall -2 \solo \soloDruhy #}))


% ensure empty is empty
% it is used in make-one-voice-vocal-staff-fixed for an empty voice
% which fixes the context concatenation for single voice lyrics

#(set! empty #f)

SATB =
<<
  \make-one-voice-vocal-staff-fixed "solo" "treble"
  \context ChoirStaff <<
    \make-one-voice-vocal-staff "zeny" "treble"
    #(if TwoVoicesPerStaff
      #{
        \make-two-vocal-staves-with-stanzas
          "WomenDivided" "treble" "MenDivided" "bass"
          "sopran" "alt" "tenor" "bas"
          #satb-lyrics-variable-names
      #}
      #{
        <<
          \make-one-voice-vocal-staff-fixed "sopran" "treble"
          \make-one-voice-vocal-staff-fixed "alt" "treble"
          \make-one-voice-vocal-staff-fixed "tenor" "treble_8"
          \make-one-voice-vocal-staff-fixed "bas" "bass"
        >>
      #} )
    \make-one-voice-vocal-staff "muzi" "bass"
  >>
>>


totalScoreObject = {
      \totalScoreObject
      \SATB
}

#(define-missing-variables! '("globalRender") #f)

sc = #(if (and have-music (not globalRender))
        #{ \score { \SATB \layout { $(if Layout Layout) } } #})
  
\sc

#(reset-properties!)
% reseet also endPartTimeSignature separately
#(define-missing-variables! '("endPartTimeSignature") #t)