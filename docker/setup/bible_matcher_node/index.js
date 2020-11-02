require('dotenv').config()
var mysql = require('mysql2/promise');

const BibleReference = require('bible-reference/bible_reference');


function getMatchings(refs, reference) {
  return refs.filter(r => r.ref.intersectsWith(reference));
}

function getLoadedSongReferences(songs) {
  return songs.filter(s => s.bible_refs_osis).flatMap(s => s.bible_refs_osis.split(',').map(ref_str => ({
    id: s.id,
    ref: BibleReference.fromOsis(ref_str)
  })));
}

function getLoadedLiturgicalYearReadingReferences(liturgical_year_readings) {
  return liturgical_year_readings.flatMap(litYearReferenceConverter);
}

const litYearReferenceConverter = lit_year => {
  const common = {
    id: lit_year.id,
    date: lit_year.date,
    type: lit_year.reference_type
  };

  // just a buch of references in reference_others
  if (!lit_year.reference_1) {
    if (lit_year.reference_others) {
      return lit_year.reference_others.split(',').map(ref_str => ({
        ...common,
        ref: BibleReference.fromOsis(ref_str),
        cycle: '-'
      }));
    } else {
      return [];
    }
  }

  // sunday - 3 cycles
  if (lit_year.reference_2) {
    let arr = [
      {
        ...common,
        ref: BibleReference.fromOsis(lit_year.reference_1),
        cycle: lit_year.reference_3 ? 'A' : '1'
      },
      {
        ...common,
        ref: BibleReference.fromOsis(lit_year.reference_2),
        cycle: lit_year.reference_3 ? 'B' : '2'
      },
    ];

    if (lit_year.reference_3) {
      arr.push({
        ...common,
        ref: BibleReference.fromOsis(lit_year.reference_3),
        cycle: 'C'
      })
    }
    return arr;
  }

  // only reference_1

  return [{
    ...common,
    ref: BibleReference.fromOsis(lit_year.reference_1),
    cycle: '-'
  }]
}


async function doJob() {
  var connection = await mysql.createConnection({
    host     : process.env.DB_HOST,
    user     : process.env.DB_USERNAME,
    password : process.env.DB_PASSWORD,
    database : process.env.DB_DATABASE,
    port: process.env.DB_PORT
  });

  var db_res = {
    song_lyrics: [],
    liturgical_year_readings: []
  }

  // extract all song_lyrics 
  let [rows] = await connection.execute('SELECT id, bible_refs_osis from song_lyrics');
  db_res.song_lyrics = rows;

  // extract liturgical references
  [rows] = await connection.execute('SELECT id, DATE_FORMAT(date,"%Y-%m-%d") as date, reference_type, reference_1, reference_2, reference_3, references_others from liturgical_year_readings');
  db_res.liturgical_year_readings = rows;


  const loaded_song_references = getLoadedSongReferences(db_res.song_lyrics);
  const loaded_liturgical_year_readings = getLoadedLiturgicalYearReadingReferences(db_res.liturgical_year_readings);

  await connection.execute('TRUNCATE liturgical_references');

  for (const song_ref of loaded_song_references) {
    const lit_matchings = getMatchings(loaded_liturgical_year_readings, song_ref.ref);
    
    for (const lit_matching of lit_matchings){
      // console.log(lit_matching);
      await connection.execute(`INSERT into liturgical_references (song_lyric_id, type, cycle, reading, date) values (
        ${song_ref.id},
        "${lit_matching.type}",
        "${lit_matching.cycle}",
        "${lit_matching.ref.toString()}",
        "${lit_matching.date}"
      )`);
    }
  }

  connection.end(function(err) {
    if (err) throw error;
  });
}

doJob();