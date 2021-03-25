%% http://lsr.di.unimi.it/LSR/Item?u=1&id=761
%% see also http://lsr.di.unimi.it/LSR/Item?u=1&id=545

%% version 2014/03/24
%% see for snippet upgrade http://gillesth.free.fr/Lilypond/chord/
%% A little doc is also provided !

#(define (noteEvent? music)
(eq? (ly:music-property music 'name) 'NoteEvent))

#(define (expand-q-chords music); for q chords : see chord-repetition-init.ly
(expand-repeat-chords! (list 'rhythmic-event) music))

%%%%%%%%%%%%%%%%%%%%%%%%%%  extractNote  %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
#(use-modules (ice-9 receive)) %% for the use of receive


%%%%%%%%%%%%%%%%%%%%% addNote

#(define (add-note music notes-to-add)                ; music and notes-to-add as music
  (define (note->chords-arti note)                    ; note as a NoteEvent
    (receive (note-arti chord-arti)
      (partition      ; separates arti for NoteEvent from arti for EventChord
        (lambda (evt)(memq (ly:music-property evt 'name)
                       (list 'StringNumberEvent 'StrokeFingerEvent 'FingeringEvent)))
        (ly:music-property note 'articulations))
      (ly:music-set-property! note 'articulations note-arti)
      chord-arti))
  (let* ((alist      ; a list of pairs of 2 lists : '(notes . articulations)
          (reverse (let loop ((m (expand-q-chords notes-to-add)) ; q to chords
                              (p '())) ; m = music, p previous value of the list
            (case (ly:music-property m 'name)
              ((or SkipEvent SkipMusic) ; a skip in notes-to-add means : add nothing
                 (cons #f p))           ; add #f to the list
              ((NoteEvent) 
                 (acons (list m) (note->chords-arti m) p))
              ((EventChord)
                 (receive (notes arti) ; separates notes from scripts, dynamics etc
                   (partition noteEvent? (ly:music-property m 'elements))
                   (if (pair? notes)(acons notes arti p) p)))
              (else (let ((e (ly:music-property m 'element)))
                 (fold loop
                       (if (ly:music? e)(loop e p) p)
                       (ly:music-property m 'elements))))))))
        (entry #f)  ; will be (car alist)
        (entry? (lambda() (and
                  (pair? alist)
                  (begin (set! entry (car alist))
                         (set! alist (cdr alist))
                         entry))))
        (do-add (lambda (notes arti)
                  (let* ((dur (ly:music-property (car notes) 'duration))
                         (new-notes (map            ; fix all durations to dur
                           (lambda(evt)(ly:music-set-property! evt 'duration dur)
                                       evt)
                           (car entry)))            ; the list of new notes
                         (new-arti (cdr entry)))    ; the articulations
                     (append new-notes notes new-arti arti)))))
    ;; combine in chords, each element of alist with notes of music  
   (map-some-music
     (lambda(x)
       (case (ly:music-property x 'name)
           ((NoteEvent)(if (entry?)
              (make-event-chord (do-add (list x) (note->chords-arti x)))
              x))
           ((EventChord)
              (if (entry?)(receive (notes arti) ; separates notes from scripts, dynamics etc
                (partition noteEvent? (ly:music-property x 'elements))
                (if (pair? notes)(ly:music-set-property! x 'elements (do-add notes arti)))))
              x)
           (else (and (ly:music-property x 'duration #f) x)))) ; #f means : go deeper
     (expand-q-chords music))))


#(define (tiny-tweak-recursive new-font-size music)
  (let ((es (ly:music-property music 'elements))
    (e (ly:music-property music 'element))
    (p (ly:music-property music 'pitch)))
  (if (pair? es)
    (ly:music-set-property!
    music 'elements
    (map (lambda (el) (tiny-tweak-recursive new-font-size el)) es)))
  (if (ly:music? e)
    (ly:music-set-property!
    music 'element
    (tiny-tweak-recursive new-font-size e)))
  (if (ly:pitch? p)
    (ly:music-set-property! music 'tweaks (list (cons (quote font-size) new-font-size))))
    music))


addNoteSmall = #(define-music-function (parser location small-font-size music notes)
                                                          (number? ly:music? ly:music?)
(add-note music (tiny-tweak-recursive small-font-size notes)))