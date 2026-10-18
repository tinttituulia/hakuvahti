<?php

/**
 * Kerran päivässä ajettava scripti.
 * 
 * Hakee Wilman rajapinnasta käyttäjän hakusanoja vastaavat kurssit,
 * ja lähettää niistä käyttäjälle koosteen sähköpostiin.
 * 
 * @author Annastiina Koivu
 * @author Ville Kähkönen
*/

// Vaadittavat tiedostot
require_once '../../config/config.php';
require_once HELPERS_DIR . 'DB.php';
require_once MODEL_DIR . 'kurssit.php';
require_once MODEL_DIR . 'kayttaja.php';
require_once MODEL_DIR . 'hakusana.php';
require_once 'email_kayttajalle.php';

// Testikäyttöön tarkoitettu tulostus
echo "Tietokanta: " . $_SERVER["DB_DATABASE"] . "\n";
echo "Käyttäjä: " . $_SERVER["DB_USERNAME"] . "\n";
echo "----------\n";

// Haetaan kaikki kurssit
$kurssit_haun_mukaan = getCoursesWithSearchData();
// Haetaan kaikki käyttäjät
$kayttajat = hae_kayttajat();

echo "Käyttäjiä tietokannassa: " . count($kayttajat) . " kpl\n";

// Käydään käyttäjät yksitellen läpi
foreach ($kayttajat as $kayttaja => $tiedot) {
    $idkayttaja = $tiedot['idkayttaja'];
    $email = $tiedot['email'];

    // Haetaan kyseisen käyttäjän kaikki hakusanat
    $kayttaja_hakusanat = hae_kayttaja_hakusanat($idkayttaja);

    $loytyneet_kurssit = array();

    // Käydään hakusanat yksitellen läpi
    foreach ($kayttaja_hakusanat as $key => $value) {
        $hakusana = $value['hakusana'];
        $hakutulos = array();

        // Käydään läpi koulu kerrallaan
        foreach($kurssit_haun_mukaan as $schooldata) {
            if (!isset($schooldata["Courses"])) continue;
            // Käydään koulun kurssit yksitellen läpi
            foreach ($schooldata["Courses"] as $coursedata) {
                if(isset($coursedata["text"])) {
                    $teksti = $coursedata["text"];
                    
                    // Etsitään hakusanaa kurssidatasta
                    if(stripos($teksti, $hakusana) !== false) {
                        $kurssi_tiedot = array(
                            "Koulutus" => $coursedata['Koulutus'],
                            "Oppilaitos" => $coursedata['Oppilaitos'],
                            "Tutkintotyyppi" => $coursedata['Tutkintotyyppi'],
                            "Linkki" => $coursedata['Tutkinnon perusteet']
                        );
                        $hakutulos[] = $kurssi_tiedot;
                    }
                }
            }
        }

        // Tallennetaan hakusana vain, jos sillä oikeasti löytyi kursseja
        if (count($hakutulos) > 0) {
            $loytyneet_kurssit[$hakusana] = $hakutulos;
        }
    }

    // Katsotaan löytyikö hakusanoilla kursseja
    if (count($loytyneet_kurssit) > 0) {

        echo "\nKäyttäjä: " . $email . "\n";
        echo "--------------------------------------\n";
        foreach ($loytyneet_kurssit as $hakusana => $hakutulos) {
            echo "HAKUSANALLA '" . $hakusana . "' löytyi " . count($hakutulos) . " tulosta:\n";
            echo "+++++++\n";

            foreach ($hakutulos as $kurssi) {
                echo "Koulutus:\t" . $kurssi['Koulutus'] . "\n";
                echo "Oppilaitos:\t" . $kurssi['Oppilaitos'] . "\n";
                echo "Tutkintotyyppi:\t" . $kurssi['Tutkintotyyppi'] . "\n";
                echo "Linkki:\t\t" . $kurssi['Linkki'] . "\n";
                echo "-------\n";
            }
            echo "\n";
        }

        // Lähetetään kooste käyttäjälle.
        $subject = "Hakusanasi löytyi koulutuksista";
        $templ = 'lapikaynti';
        $muuttujat = ['kurssit' => $loytyneet_kurssit];

        $lahetys_onnistui = laheta_email($email, $subject, $templ, $muuttujat);
        echo "Sähköpostin lähetys osoitteeseen $email: " . ($lahetys_onnistui ? "ONNISTUI" : "EPÄONNISTUI") . "\n";
    } else {
        echo "Käyttäjälle $email ei löytynyt osumia hakusanoilla.\n";
    }
}

?>