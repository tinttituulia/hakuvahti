<?php

require_once HELPERS_DIR . 'DB.php';

/**
 * Lisää uuden hakusanan käyttäjälle.
 *
 * @author Esko Taivalmäki
 *
 * @param  int    $idkayttaja Käyttäjäid, jolle hakusana lisätään.
 * @param  string $hakusana   Lisättävä hakusana.
 * 
 * @return int                Lisätyn hakusanan pääavaintunniste.
 */
function lisaaHakusanaKayttajalle($idkayttaja,$hakusana) {
  DB::run('INSERT INTO hakuvahti_hakusana (idkayttaja,hakusana) VALUE  (?,?);',[$idkayttaja,$hakusana]);
  return DB::lastInsertId();
}

/**
 * Hakee tietokannasta yksittäisen käyttäjän hakusanat käyttäjän id:llä.
 * 
 * @author Annastiina Koivu
 * 
 * @param string $idkayttaja    käyttäjän id tietokannassa
 * 
 * @return array                palauttaa hakusanat assosiatiivisessa taulukossa
 * 
 */
function hae_kayttaja_hakusanat($idkayttaja) {
    $kysely = "SELECT hakusana FROM hakuvahti_hakusana WHERE idkayttaja = $idkayttaja";

    $tulos = DB::run($kysely)->fetchAll(PDO::FETCH_ASSOC);

    return $tulos;
}

/**
 * Poistaa hakusanan käyttäjältä.
 *
 * @author Vee Kankaristo
 *
 * @param  int    $idkayttaja Käyttäjäid, jolta poistetaan hakusana.
 * @param  int    $idhakusana Hakusanaid, joka poistetaan.
 */
function poistaHakusanaKayttajalta($idkayttaja, $idhakusana) {
  DB::run('DELETE FROM hakuvahti_hakusana WHERE idkayttaja = ? AND idhakusana = ?',[$idkayttaja, $idhakusana]);
}

?>