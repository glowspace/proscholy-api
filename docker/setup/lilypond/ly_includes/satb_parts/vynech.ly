#(define (MM->Script-text evt)
(if (eq? 'MultiMeasureTextEvent (ly:music-property evt 'name))
  (let ((res (make-music 'TextScriptEvent)))
    ;; (display (ly:music-mutable-properties evt)) ; if you are curious ...
    (for-each (lambda(props)
                 (ly:music-set-property! res (car props) (cdr props)))
               (ly:music-mutable-properties evt))
    res)
  evt))

vynech = #(define-music-function (parser location music) (ly:music? )
(map-some-music 
 (lambda(x)
   (let ((dur (ly:music-property x 'duration #f)))
      (and (or dur (eq? 'EventChord (ly:music-property x 'name)))
           (let ((skip (make-music 'SkipEvent 'duration
                          (or dur (make-duration-of-length (ly:music-length x)))))
                 (artis (let ((elts (ly:music-property x 'elements #f)))
                          (if elts (filter (lambda(y)   ;; EventChord
                                             (not (eq? 'NoteEvent (ly:music-property y 'name))))
                                           elts)
                                   (map MM->Script-text (ly:music-property x 'abcd '()))))))
             (ly:music-set-property! skip 'articulations artis)
             skip))))
  music))