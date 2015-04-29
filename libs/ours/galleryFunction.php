<?php
/**
 *
 * @author Dušan Vala as rellik
 * http://mmrs.rellik.eu
 * 
 */
/* * ********* Funkce na zobrazení náhodného obrázku ********* */
$galleryExist = $this->model->getGallery()->count('*');
if ($galleryExist != 0) {

    function randImage($galerie) {
        $id_galerie = WWW_DIR . '/gallery/' . $galerie . '/nahledy';
        $pocet = 0;
        if ($ra = opendir($id_galerie)) {
            $vypis_file = array();
            while ($soubor_file = readdir($ra)) {
                if (!is_dir($soubor_file) AND $soubor_file != "." AND $soubor_file != "..") {
                    $pocet++;
                    $nahodny = rand(1, $pocet);
                    $vypis_file[$nahodny] = $soubor_file;
                }
            }
        }
        if ($pocet >= 1) {
            while (list($celkem, $nazev) = each($vypis_file)) {
                $vytvor_nahled = $nazev;
                if ($celkem == 1) {
                    $wiew = $vytvor_nahled;
                }
            }
            return $wiew;
            closedir($ra);
        } 
    }

}

/* * ************* zjištění počtu obrázků ******************* */
$galleryExist = $this->model->getGallery()->count('*');
if ($galleryExist != 0) {

    function pocetImage($galerie) {
        $id_galerie = WWW_DIR . '/gallery/' . $galerie . '/nahledy';
        $pocet = 0;

        if ($ra = opendir($id_galerie)) {
            $vypis_file = array();
            while ($soubor_file = readdir($ra)) {
                if (!is_dir($soubor_file) AND $soubor_file != "." AND $soubor_file != "..") {
                    $pocet++;
                }
            }
            return $pocet;
        }
    }

}

function addFolder($slozka) {
    if (!is_dir( WWW_DIR."/gallery")) {
    mkdir(WWW_DIR."/gallery", 0777);
}
    mkdir(WWW_DIR.'/gallery/' . $slozka, 0777);
    chmod(WWW_DIR.'/gallery/' . $slozka, 0777);
    mkdir(WWW_DIR.'/gallery/' . $slozka . '/nahledy', 0777);
    chmod(WWW_DIR.'/gallery/' . $slozka . '/nahledy', 0777);
}