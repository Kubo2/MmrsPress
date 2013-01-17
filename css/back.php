<?php

header('content-type: text/css');
/* Nastavení barvy titulu stránky.
  1. položka - Barva písma titulu.
  2. položka - Barva stínu titulu.
 */
$title = array('ffffff', '111111');

// nastavení výšky hlavičky (loga). Výchozí výška je 120px. Pro jiné rozměry je nutné si vytvořit novou grafiku. 
$heightHeader = '120';


/* Next do not changes! */


$css = htmlspecialchars(base64_decode($_GET['css']));
$match = array();
preg_match_all("/{([^ ,.]+)\}/U",$css,$match, PREG_SET_ORDER);
//print_r($match);
$color = $match[0][1]; // pozadí hlavičky
$logo = $match[1][1]; // obrázek hlavičky
$backgroundRepeat = $match[2][1]; // umístění obrázku hlavičky
$widthWeb = $match[3][1]; // šířka stránky
$backColor = $match[4][1]; // barva pozadí webu
$margin = $match[5][1]; // odsazení textu v hlavičce
$left = $match[6][1]; // nastavení levý panel
$right = $match[7][1]; // nastavení pravý panel
$Acolor = $match[8][1]; // barva odkazů
$ContentColor = $match[9][1]; // barva pozadí obsahu
$AcolorH = $match[10][1]; // barva odkazů hover
$pageColor = $match[11][1]; // barva layoutu (pozadí) stránky
$textColor = $match[12][1]; // barva textu
$hColor = $match[13][1]; // barva nadpisů
$footer = $match[14][1]; // barva pozadí patičky
$menuA = $match[15][1]; // hlavní menu
$menuH = $match[16][1]; // hlavní menu hover
$galleryTd = $match[17][1]; // barva buňky náhledu dotogalerie
$galleryTdH = $match[18][1]; // barva buňky náhledu dotogalerie hover
$tdColor = $match[19][1]; // barva písma v buňce náhledu galerie


if (empty($color)) {
    $soubor = '../logo/logo_black.png';
} else {
    $soubor = '../logo/logo_' . $color . '.png';
}
if (!$logo) {
    $logo = '../logo/logo_mmrs.png';
} else {
    $logo = '../logo/logo_' . $logo . '.png';
}
if ($backgroundRepeat == 'r') {
    $repeat = 'right';
}
if ($backgroundRepeat == 'l') {
    $repeat = 'left';
}
if ($backgroundRepeat == 'rp') {
    $repeatX = 'repeat-x';
} else {
    $repeatX = 'no-repeat';
}
?>
/* 
    Document   : back.php (dynamic css)
    Created on : 25.2.2012, 10:22:35
    Author     : Dušan Vala as rellik
    Home page  : http://mmrspress.eu
    Description: Purpose of the stylesheet follows.
*/

/******************** rozložení stránky *************/
body {
    color: <?php echo '#'.$textColor; ?>;
    background-color: <?php echo '#'.$backColor; ?>;
}
#header {
    height: <?php echo $heightHeader; ?>px;
    background-image: url(<?php echo $soubor; ?>);
    border-bottom: 1px solid <?php echo '#'.$ContentColor; ?>;
}
#header div.title {
    background-repeat: <?php echo $repeatX; ?>;
    margin-left: <?php echo $margin; ?>px;
    color: #<?php echo $title[0]; ?>;
    text-shadow: #<?php echo $title[1]; ?> 1px 1px 2px;
}
#header-inner {
    width: 100%;
    height: <?php echo $heightHeader; ?>px;
    background-image: url(<?php echo $logo; ?>);
    background-repeat: <?php echo $repeatX; ?>;
    background-position: <?php echo $repeat; ?>;
}
#page {
    width: <?php echo $widthWeb; ?>;
    background-color: <?php echo '#'.$pageColor; ?>; 
}
.rubriky_layout {
    border: 1px solid <?php echo '#'.$ContentColor; ?>;
    background-color: <?php echo '#'.$ContentColor; ?>;
}
.rubriky, rubriky logged-in, rubriky icon {
    background-color: <?php echo '#'.$footer; ?>;
    border-bottom: 2px solid <?php echo '#'.$ContentColor; ?>;
    color:  <?php echo '#'.$menuA; ?>;
}
.rubriky_layout a {
    color: <?php echo '#'.$menuA; ?>;
    }
.rubriky_layout a:hover {
    color: <?php echo '#'.$menuH; ?>;
    }
#content {
    <?php 
    $right_menu = $right;
    $left_menu = $left;
    if ($left_menu == 0 AND $right_menu  == 0){
         echo "margin: 10px;\n";
        }
    if ($left_menu == 1 AND $right_menu  == 1) {
         echo "margin: 10px 220px 10px 220px;\n";
        }
    if ($left_menu == 1 AND $right_menu  == 0) {
         echo "margin: 10px 10px 10px 220px;\n"; 
        }
    if ($left_menu == 0 AND $right_menu  == 1) {
         echo "margin: 10px 220px 10px 10px;\n"; 
        }
    ?>
    background: <?php echo '#'.$ContentColor; ?>; 
}
#footer {
    background-color: <?php echo '#'.$footer; ?>;
    border-top: 1px solid <?php echo '#'.$ContentColor; ?>;
    color:  <?php echo '#'.$menuA; ?>;
}
#footer a {
    color: <?php echo '#'.$menuH; ?>;
}
/*********************** Navigace. ******************/
/*********************** levé menu ******************/
.mainMenu a {
    color: <?php echo '#'.$menuA; ?>;
    border-bottom: 1px solid <?php echo '#'.$menuA; ?>;
    border-left: 3px solid <?php echo '#'.$menuA; ?>;
}
.mainMenu a:hover {
    color: <?php echo '#'.$menuH; ?>;
    border-bottom-color: <?php echo '#'.$menuH; ?>;
    border-left: 3px solid <?php echo '#'.$menuH; ?>;
}
.rubrikyMenu a {
    color: <?php echo '#'.$menuA; ?>;
}
.rubrikyMenu a:hover {
    color: <?php echo '#'.$menuH; ?>;
}
#sidebar .current a {
    border-bottom-color: <?php echo '#'.$menuH; ?>;
    border-left: 3px solid <?php echo '#'.$menuH; ?>;
}
/************* horizontální menu *******************/
#sidebarTop {
    background-color: <?php echo '#'.$footer; ?>;
}
/* Root Menu */ 
ul#mainMenuTop a { 
    color: <?php echo '#'.$menuA; ?>;
    border-right: 1px solid <?php echo '#'.$menuA; ?>;
    text-align: left;
    padding-left: 15px;
}
/* Root Menu Hover Persistence */ 
ul#mainMenuTop a:hover,ul#mainMenuTop li:hover a,ul#mainMenuTop li.iehover a {
    background: <?php echo '#'.$footer; ?>;
    color: <?php echo '#'.$menuH; ?>;
}
/* 2nd Menu */ 
ul#mainMenuTop li:hover li a,ul#mainMenuTop li.iehover li a {
    background-color: <?php echo '#'.$footer; ?>;
    border: 1px solid <?php echo '#'.$menuA; ?>;
}
/* 2nd Menu Hover Persistence */ 
ul#mainMenuTop li:hover li a:hover,ul#mainMenuTop li:hover li:hover a,ul#mainMenuTop li.iehover li a:hover,ul#mainMenuTop li.iehover li.iehover a {
    background-color: <?php echo '#'.$pageColor; ?>;
    color: <?php echo '#'.$menuA; ?>;
}
/************************ Titulky *******************/
h1 {
    color: <?php echo '#'.$hColor; ?>;
}
h2 {
    color: <?php echo '#'.$hColor; ?>;
}
/************************* Odkazy *******************/
a {
    color: <?php echo '#'.$Acolor; ?>;
}
a:hover {
    color: <?php echo '#'.$AcolorH; ?>;
}
/********************* Stránkování *********************/
.paginator a {
    border: 1px solid <?php echo '#'.$Acolor; ?>;
}
.paginator .current {
    background: <?php echo '#'.$Acolor; ?>;
    border: 1px solid <?php echo '#'.$AcolorH; ?>;
    color: <?php echo '#'.$AcolorH; ?>;
}
/********************* formátování obsahu *********************/
.bookUser {
        background-color: <?php echo '#'.$footer; ?>;
        border: 1px solid <?php echo '#'.$AcolorH; ?>;
}
.bookUser a {
    color: <?php echo '#'.$Acolor; ?>;
}
.bookWiew {
    border: 1px solid <?php echo '#'.$pageColor; ?>;
}
/******************* Fotogalerie *********************/
.galerie td {
    background-color: <?php echo '#'.$galleryTd; ?>;
    color: <?php echo '#'.$tdColor; ?>;
}
.galerie td:hover {
    background-color: <?php echo '#'.$galleryTdH; ?>;
}
.galerie a {
    color: <?php echo '#'.$tdColor; ?>;
}