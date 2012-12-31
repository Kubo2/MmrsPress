<?php 
    header ('content-type: text/css');
// hlavní barvy stránky
// 1. položka - Pozadí horního menu, orámování stránky, pozadí horního panelu odkazových buněk, pozadí stránkování (aktuální strana), orámování odkazů stránkování.
// 2. položka - Spodní orámování panelu odkazových buněk, dělící čára horního menu, hover podstránek horního menu, orámování aktuálního odkazu strákování
// 3. položka - Hover horního menu, barva písma aktuálního odkazu strákování
// 4. položka - Barva písma horního menu, barva písma horního panelu odkazových buněk, barva písma v patičce, barva písma záhlaví příspěvků v knize návštěv
// Barvy 1-3 jsou seřazeny od nejtmavší po světlejší (u některých barev je vyjímka)    
$black = array('111111', '333333', '666666', 'eeeeee');
$grey = array('666666', '888888', 'aaaaaa', 'ffffff');
$blue = array('336699', '3366CC', '6699CC', 'cccccc');
$green = array('006666', '669999', '339999', 'ffffff');
$red = array('CC3333', 'CC6666', 'FF9999', 'ffffff');
$brown = array('CC9933', 'ddbb99', 'CC9933', 'ffffff');
$limet = array('339933', '66CC66', '99CC66', 'ffffff');
$purple = array('993399', '9933aa', 'CC66CC', 'ffffff');

/* Nastavení barvy titulu stránky.
1. položka - Barva písma titulu.
2. položka - Barva stínu titulu.
*/
$title = array('ffffff','111111');

// nastavení výšky hlavičky (loga). Výchozí výška je 120px. Pro jiné rozměry je nutné si vytvořit novou grafiku. 
$heightHeader = '120';

// nasatvení šířky webu vůči zozlišení obrazovky. Doporučené rozmezí 70-100%.
$widthWeb = '80%';


/* Dál neměnit! Next do not changes! */
$color = htmlspecialchars($_GET['color']);
if(empty($color)) {
    $soubor = '../logo/logo_black.png';
} else {
    $soubor = '../logo/logo_'.$color.'.png';
}

if($color == 'black' OR $color == '') { $colors = $black; }
if($color == 'grey') { $colors = $grey; }
if($color == 'blue') { $colors = $blue; }
if($color == 'green') { $colors = $green; }
if($color == 'red') { $colors = $red; }
if($color == 'brown') { $colors = $brown; }
if($color == 'limet') { $colors = $limet; }
if($color == 'purple') { $colors = $purple; }

if(!isset($_GET['logo'])) {
   $logo = '../logo/logo_mmrs.png'; 
} else {
   $logo = '../logo/logo_'.htmlspecialchars($_GET['logo']).'.png'; 
}
$backgroundRepeat = htmlspecialchars($_GET['float']);
if($backgroundRepeat == 'r') { $repeat = 'right'; }
if($backgroundRepeat == 'l') { $repeat = 'left'; }
if($backgroundRepeat == 'rp') { $repeatX = 'repeat-x'; } else { $repeatX = 'no-repeat'; }

$margin = htmlspecialchars($_GET['padding']);
if ($margin == '') { $marginTitle = '150'; } else { $marginTitle = $margin; }
?>
/******************** rozložení stránky *************/
body {
    font-size: small;
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    margin: 0;
    padding: 0;
    background:#aaa;
    background-image:-webkit-gradient(linear,left top,left bottom,from(#333),to(#aaa));
    background-image:-moz-linear-gradient(#333,#aaa);
    -pie-background:linear-gradient(#333,#aaa);
    background-repeat: no-repeat;
    background-attachment: fixed;
}
img, a img {
    border: none;
}
#header {
    background-color: none;
    background-repeat: repeat-x;
    height: <?php echo $heightHeader; ?>px;
    border-radius: 12px 12px 0 0;
    background-image: url(<?php echo $soubor; ?>);
}
#header div.title {
    padding-top: 25px;
    margin-left: <?php echo $marginTitle; ?>px;
    float: left;
    font-size: 300%;
    color: #<?php echo $title[0]; ?>;
    text-shadow: #<?php echo $title[1]; ?> 1px 1px 2px;
}
#header a {
    color: #FFF;
    text-decoration: none;
}
#header div.user {
    padding: 12px 1em;
    float: right;
    color: #FFF;
}
#header div.user span {
    font-weight: bold;
}
#header-inner {
    width: 100%;
    height: <?php echo $heightHeader; ?>px;
    border-radius: 12px 12px 0 0;
    background-image: url(<?php echo $logo; ?>);
    background-repeat: <?php echo $repeatX; ?>;
    background-position: <?php echo $repeat; ?>;
}
#page {
    width: <?php echo $widthWeb; ?>;
    min-width: 800px;
    margin: 0px auto;
    border: 1px solid <?php echo '#'.$colors[0]; ?>;
    position: relative;
    border-radius: 12px;
    margin-top: 15px;
    margin-bottom: 15px;
    background-color: #f1f1f1; 
    box-shadow: 0 10px 10px rgba(0, 0, 0, .1);
}
#container {
    margin: 0 auto;
}
#sidebar {
    float: left;
    width: 210px;
}
#sidebar_right {
    float: right;
    width: 210px;
}
.rubriky_layout {
    border: 1px solid #aaa;
    border-radius: 5px;
    margin: 3px;
}
.center {
    text-align: center;
    margin-top: 3px;
    display: block;
}
.rubriky {
    display: block;
    background-color: <?php echo '#'.$colors[0]; ?>;
    border-bottom: 2px solid <?php echo '#'.$colors[1]; ?>;
    text-shadow: #000 1px 1px 2px;
    padding-left: 3px;
    color:  <?php echo '#'.$colors[3]; ?>;
    font-weight: bold;
    font-size: 110%;
    border-radius: 5px 5px 0 0;
}
.rubriky img {
    padding-top: 3px;
    margin-right: 5px;
}
.rubriky_layout a {
    padding-left: 10px;
    color: #333;
    }
.rubriky_layout a:hover {
    color: #333;
    }
.rss {
    float: right;
    margin: 5px 10px 0 0;
    position: relative;
    }
.rss_menu {
    padding-left: 5px;
    display: block;
    }
#sidebar div.title {
    font-weight: bold;
    margin: 1em 0 1ex;
    font-size: 120%;
}
#content {
    <?php 
    $right_menu = intval($_GET['margin']);
    $left_menu = intval($_GET['left']);
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
    border-left: 1px solid #eee;
    padding: 10px;
    background: #fff; 
    border-radius: 12px; 
    box-shadow: 0 10px 10px rgba(0, 0, 0, .1);
}
#footer {
    height: 30px;
    width: 100%;
    background-color: <?php echo '#'.$colors[2]; ?>;
    text-align: center;
    color:  <?php echo '#'.$colors[3]; ?>;
    margin-top: 1ex;
    border-radius:  0 0 12px 12px;
    border-top: 1px solid #002756;
    clear: both;
    vertical-align: top;
    line-height: 2;
    background-repeat: repeat-x;
    background-image: url(<?php echo $soubor; ?>);
}
#footer a {
    color: #fff;
}
#viewAdmin {
    width: 100%;
    height: 25px;
    background-color: #444;
    color: #BFBFBF;
    border-bottom: 2px solid #999;
}
#viewAdmin div {
    float: right;
    width: auto;
    padding-right: 10px;
}
#viewAdmin .mmrs {
    padding-left: 10px;
}
#viewAdmin:first-letter {
    color: #0099cc;
}
#viewAdmin a {
    color: #C1D2E1;
    text-decoration: none;
}
/*********************** Navigace. ******************/
/*********************** levé menu ******************/
div.task-lists ul {
    margin: 0;
    padding: 0;
    list-style: none;
    display: block;
}
div.task-lists li {
    display: block;
    margin: 1ex;
}
.mainMenu a {
    text-decoration: none;
    display: block;
    color: #666;
    border-bottom: 1px solid #ddd;
    font-size: 125%;
    padding: 0 1ex 1px;
    border-left: 3px solid #BFBFBF;
    text-align: left;
}
.mainMenu a:hover {
    color: #333;
    border-bottom-color: #555;
    border-left: 3px solid #7F7F7F;
}
.rubrikyMenu {
    list-style: none;
}
.rubrikyMenu a {
    color: #666;
    text-decoration: none;
    font-size: 125%;
}
.rubrikyMenu a:hover {
    color: #666;
    font-size: 125%;
    text-decoration: underline;
}
.podmenu {
    font-size: 80%;
}
#sidebar .current a {
    border-bottom-color: #555;
    border-left: 3px solid #7F7F7F;
}
/************* horizontální menu *******************/
#sidebarTop {
    width: 100%;
    background-color: <?php echo '#'.$colors[0]; ?>;
    font-size: 110%;
}
/* Author: Craig Erskine Description: Dynamic Menu System - Horizontal/Vertical */ 
#mainMenuTop {
    width:100%;
    height: 35px;
    margin: 0;
    padding: 0;
    list-style: none;
}
#mainMenuTop ul { 
    width: 160px;
    /* Sub Menu Width */ 
    margin: 0;
    list-style: none;
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
} 
ul#mainMenuTop ul ul,ul#mainMenuTop ul ul ul {
    top: 0;
    left: 100%;
}
#mainMenuTop li {
    float: left;
    display: inline;
    position: relative;
}
ul#mainMenuTop ul li {
    width: 100%;
    display: block;

}
/* Root Menu */ 
ul#mainMenuTop a { 
    width: 125px;
    display: block;
    height: 35px;
    line-height: 2.2;
    color: <?php echo '#'.$colors[3]; ?>;
    font-size 110%;
    font-weight: bold;
    text-decoration: none;
    text-align: center;
    border-right: 1px solid <?php echo '#'.$colors[1]; ?>;
}
/* Root Menu Hover Persistence */ 
ul#mainMenuTop a:hover,ul#mainMenuTop li:hover a,ul#mainMenuTop li.iehover a {
    background: <?php echo '#'.$colors[2]; ?>;
    color: <?php echo '#'.$colors[3]; ?>;
}
/* 2nd Menu */ 
ul#mainMenuTop li:hover li a,ul#mainMenuTop li.iehover li a {
    float: none;
    font-weight: normal;
    background-color: <?php echo '#'.$colors[2]; ?>;
    width: 150px;
    padding-left: 3px;
    text-align: left;
    border-radius:  0 5px 5px 0;
    border: 1px solid <?php echo '#'.$colors[0]; ?>;
}
/* 2nd Menu Hover Persistence */ 
ul#mainMenuTop li:hover li a:hover,ul#mainMenuTop li:hover li:hover a,ul#mainMenuTop li.iehover li a:hover,ul#mainMenuTop li.iehover li.iehover a {
    background-color: <?php echo '#'.$colors[1]; ?>;
    color: <?php echo '#'.$colors[3]; ?>;
}
/* Hover Function - Do Not Move */ 
ul#mainMenuTop li:hover ul ul,ul#mainMenuTop li:hover ul ul ul,ul#mainMenuTop li.iehover ul ul,ul#mainMenuTop li.iehover ul ul ul { 
    display: none;
}
ul#mainMenuTop li:hover ul,ul#mainMenuTop ul li:hover ul,ul#mainMenuTop ul ul li:hover ul,ul#mainMenuTop li.iehover ul,ul#mainMenuTop ul li.iehover ul,ul#mainMenuTop ul ul li.iehover ul { 
    display: block;
}
#sidebarTop .current {
    background-image: url("../images/active.png");
    background-repeat: no-repeat;
    z-index: 10;
}
/************************ Titulky *******************/
h1 {
    color: #404040;
    font-size: 200%;
    padding: 0 0 4px;
    margin: 0 0 1em;
}
h2 {
    font-size: 150%;
    margin: 1.5em 0 1ex;
}
/************************* Odkazy *******************/
a {
    color: #0069D6;
}
a:hover {
    color: #004382;
}
/************************ Tabulky *******************/
table {
    width: 100%;
    border-collapse: separate;
    text-align: left;
    margin-bottom: 1.4em;
}
thead th {
    font-size: 112%;
}
th, td {
    padding: 5px 6px 4px;
}
tr.even {
    background-color: #FAFAFA;
}
tr.odd {
    background-color: #eaeaea;
}
table.tasks tr.done {
    color: #888;
    text-decoration: line-through;
}
table.tasks .created {
    font-size: smaller;
    width: 70px;
    text-align: right;
}
table.tasks .tasklist {
    width: 65px;
}
table.tasks .user {
    font-size: smaller;
}
table.tasks .action {
    width: 75px;
}
tr.notice td {
    text-align: center;
    border: 1px solid #c0c0c0;
    background-color: #f6f6f6;
    padding: 1em;
}
#news label {
    font-size: 80%;
}
.counter {
    font-weight: bold;
    line-height: 0.8;
}
/******************************** Formuláře *********************/
fieldset {
    border-top: 1px solid #bbb;
    border-width: 1px 0;
    margin: 1.5em 0;
    padding: 1ex 0;
}
legend {
    font-weight: bold;
    color: #105CB6;
    margin-left: 1em;
}
label {
    font-weight: bold;
}
form div.pair {
    margin-bottom: 1ex;
}
form div.pair label {
    display: block;
    width: 130px;
    text-align: right;
    float: left;
    line-height: 2;
    vertical-align: middle;
}
form div.pair div.input {
    margin-left: 150px;
}
form div.pair div.input label {
    width: auto;
    display: inline;
    float: none;
    line-height: normal;
}
form ul.error {
    margin: 0;
    padding: 0;
    list-style: none;
}
form ul.error li {
    display: block;
    padding: .8em;
    margin-bottom: 1em;
    border: 2px solid #FBC2C4;
    background: #FBE3E4;
    color: #8a1f11;
}
input[type=checkbox] {
    vertical-align: middle;
}
.task-form input[type=submit] {
    float: right;
}
/******************* Flash zprávičky ******************/
div.flash {
    padding: .8em;
    margin-bottom: 1em;
    border: 2px solid #ddd;
}
div.flash.success {
    background: #E6EFC2;
    color: #264409;
    border-color: #C6D880;
}
div.flash.info {
    background: #FFF6BF;
    color: #514721;
    border-color: #FFD324;
}
div.flash.error {
    background: #FBE3E4;
    color: #8a1f11;
    border-color: #FBC2C4;
}
/********************** Ikonky *************************/
.icon {
    padding-left: 24px;
    background-position: 0 50%;
    background-repeat: no-repeat;
}
.icon.tick {
    background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAWVJREFUeNpi/P//PwNdgVsnw38QJkuzYxvD/3c/JoIxiE2y5idfmv/P2sQAxk+/NoJdwkKs5sV5zQzbj9QyMLAyMDACxb7/f4eqyB1omjsWvzkBNd/+XPR/1h6G/zOBePZehv+XPsX/d4J4wRiu6MqnOCCGS0DEW4GKPyb8n30Q6OwDQM2HQJoTUDWDbD37we//7GNABUcZ/p967//foZkBjEFskPgsoPgcIH36gz+qZiBggfuHmYHhH1Dqwq2NDLVpNmBJEJuBCehnIFZTsWOomLaRYV8VgwlQ6izMAJB+Y5c2hjOlqboMj59cZvj3j4EBlrYYgbJMQM2yMroMXbMvM+xF0wwCQHsZnt/by7DlheCrNAd7WYYfPz4x/AfZCpRhBsaRiIQsw4Q5t7FqRgcgl/xf+Zz7/9wbDGAMYrug+ZkQMHYFalj9khuMXUnUDDfEExgznp3kaYYbQqxmRkqzM0CAAQBWbMG1YQFlxwAAAABJRU5ErkJggg==");
}
.icon.user {
    background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAfNJREFUeNqcU89rE0EYfZNsto1rbdUmofUgmoOWQIm/EOtB7aUIpfRWL148eVOwf0FO4g8oWIpHvRhPQvHeixSRWooptVVIrEnRpkk2+yM7s7PdzbpJKIaykeCD4YNv3nvzzcz3Edd1cQBCSDM+unk8TgQsRwfDMZO7kGVeDAXd60+XqtnGfrsmAB/sO1i8cmkwlkyexbmROELSQEyrYdGP62vQEyYJUZQQEE8gGIogQCRYNhJdG1jcxs8dDdQSMXI5gYnpMTi240eF4JekhokHC8/BC79RlVVIx4JwLIauK+CmlUapBKWiQJUVL8oQROdl1wYr9pnU8koeVNfADB1beQOvM/VUVwYTL7bTY6m1TRa9he8/dvFp7RsKQhL332m/bs9vv/3nG9x4/GXvzgUpcvV8P4YqFF9zDo6GOIZ6dzE13oePG3zGfJIZ96hRX4PhvkBk9FQY6XWGexcnce3uJEYpxUaR4U2GY/r0Eei6Gel4harKkC1zr/NqmFsqATZD3Vvp9ToqZR25sgXDoJ2vUFIoCvI+NI2h8e0zr4wWyTtGCBLkK71g1OxsUCiqWM1VcXJAQrPdD1qetObkc1YBY7yzAc19ePi+Rp85bivfpm/CK8J291Zngam/A3hoGvu9EG/THEaDnPU0qq/B/+CPAAMAQ2jaR3QERSQAAAAASUVORK5CYII=");
}
.icon.edit {
    background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAXxJREFUeNqU0s1LAkEYBvDHsK4FJXmQOmR1iaLyov9Ah4g+Dt3qVEJU9xCkIFAp6LAlBIYQ3oMIukhKSUaFH+mlukggRWuipeLX2jS7ZGDaMg3swszu79l93xngH+Nhy0eitjNSnRNC0MSK161W0jrUC9KlRnTb8xPCFGCy7JKVpWXsh53I81nEsxUEzUeEKeDaPEJMc33g7E6Mj03A/XmOcqYMIclLzxVy2G/WE/20CkjEEWtZgOuWYHBAC++VF9ymTUd7EFDI4hk1wL8BKXplXhFpW8VhSIEdy4aOvhIQm6iQx0mKU0D2na4W4AprML8XlPCfu1CPP2ihAg4i/TW44S40xmU47rRY5Hx1uCbgUgYbuYuGWBxK8eZb0xHDlOq7YWlmLAUkjidJx7AeeHQDSRqQy9H/EpiwVAIpVmhMJ0rds0BRoF8uMmMp4OS+HaeeJyhLeUBjgCPUw4ylEmJ8CS/PN9Cmm1EoKGG0+5lx9SiP/lpjxuJB+hJgAG9My8C+hsJpAAAAAElFTkSuQmCC");
}
/********************* Stránkování *********************/
.paginator {
    margin: 1em 0;
    font-size: 90%;
    width: 100%;
    text-align: center;
}
.paginator a, .paginator span {
    margin-right: 0.1em;
    padding: 0.2em 0.5em;
    color: #aaaaaa;
}
.paginator a {
    border: 1px solid <?php echo '#'.$colors[0]; ?>;
    text-decoration: none;
    color: #666;
}
.paginator span.button {
    border: 1px solid #ddd;
    color: #ddd;
}
.paginator .current {
    background: <?php echo '#'.$colors[0]; ?>;
    border: 1px solid <?php echo '#'.$colors[1]; ?>;
    color: <?php echo '#'.$colors[2]; ?>;
    font-weight: bold;
}
/********************* formátování obsahu *********************/
.right {
    float: right;
}
.poznamka {
    border-top: 1px solid #dcdcdc;
    font-size: 90%;
    color: #7F7F7F;
}
#logged-in {
    padding-left: 5px;
    color: #333;
}
#logged-in a {
    padding-left: 5px;
    color: #333;
}
#help {
    visibility: hidden; 
    position: absolute; 
    z-index: 2; 
    background-color: White;
    padding: 4px;
    border: 1px solid;
}
.red {
    color: red;
}
.green {
    color: Green;
}
.blue {
    color: Blue;
}
pre { font-size: 12px; 
      line-height: 1; 
      padding: 5px; 
      margin: 1.3em 0; 
      overflow: auto; 
      max-height: 500px; 
      background: #F1F5FB; 
      border-radius: 5px; 
      box-shadow: 0 1px 1px rgba(0, 0, 0, .1); 
}
code {
    white-space: nowrap;
    display: block;   
    border: none;
    padding: 5px;
}
.bookUser {
    background-image: url(<?php echo $soubor; ?>);
    background-repeat: repeat-x;
    background-color: <?php echo '#'.$colors[1]; ?>;
    border-bottom: 2px solid <?php echo '#'.$colors[0]; ?>;
    color: <?php echo '#'.$colors[3]; ?>;
    text-shadow: rgba(0, 0, 0, 0.5) 1px 1px 2px;
    padding: 3px;
    border-radius: 5px;
}
.bookUser a {
    color: <?php echo '#'.$colors[3]; ?>;
    text-decoration: none;
}
.bookWiew {
    border: 1px solid #e1e1e1;
    margin-bottom: 10px;
    padding: 10px;
    border-radius: 5px;
}
/******************* Fotogalerie *********************/
#aktuality td {
    border-bottom: 2px solid #cdcdcd;
    padding-bottom: 10px; 
}
.galerie {
    width: 100%;
}
.galerie td {
    width: 25%;
    text-align: center;
    vertical-align: top;
    border: 1px solid #888;
    -webkit-border-radius: 10px;
    -moz-border-radius: 10px;
    border-radius: 10px;
    background-color: #f1f1f1;
}
.galerie td:hover {
    background-color: #e1e1e1;
}
.galerie a {
    font-size: 120%;
    font-weight: bold;
    text-decoration: none;
    color: #333;
}
.users {
    border: 1px solid #bbaacc;
    border-radius: 5px; 
    background-color: #F1F5FB;
}
/*------------------POPUPS------------------------*/
.zobraz {
    margin: 0;
    padding: 0;
    visibility: hidden; 
    position: absolute; 
    top: 20%;
    z-index: 1; 
    background-color: #dcdcdc;
    border: 2px solid #999999;
    -webkit-border-radius: 10px;
    -moz-border-radius: 10px;
    border-radius: 10px;
    text-align: right;
    box-shadow: rgba(0, 0, 0, 0.5) 10px 10px 20px;
}
.zobraz a {
    padding: 0px 3px 3px 3px;
    float: right;
    margin: 5px;
    text-decoration: none;
}
