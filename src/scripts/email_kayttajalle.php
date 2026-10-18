<?php

/**
 * Lähettää käyttäjälle sähköpostia,
 * hyödyntäen Platesia ja sähköpostipohjia.
 * 
 * @author Annastiina Koivu
 * 
 * @param string $email             käyttäjän sähköpostiosoite
 * @param string $subject           viestin aihe
 * @param string $templ             mitä email-pohjaa halutaan käyttää
 * @param array $muuttujat          mahdollisuus antaa templatelle dataa taulukkona,
 *                                  esim: ['muuttuja_pohjassa' => 'arvo']
 * 
 * @return bool                     palauttaa true, jos viesti lähti
 * 
*/

function laheta_email($email, $subject, $templ, $muuttujat = null) {

    // Tuodaan vaadittavat tiedostot
    require_once '../../config/config.php';
    require_once '../../vendor/autoload.php';

    // Luodaan uusi Plates-olio sähköpostipohjille.
    $email_templates = new League\Plates\Engine(TEMPLATE_DIR . "email/");

    $message = $email_templates->render($templ, $muuttujat);

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: noreply@neutroni.hayo.fi";

    return mail($email, $subject, $message, $headers);

}

?>