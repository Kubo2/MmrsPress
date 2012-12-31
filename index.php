<?php

// Absolutní cesta souborového systému pro web
define('WWW_DIR', __DIR__);
// Vytvoření temp adresáře
if (!is_dir("log")) {
    mkdir("log", 0777);
}
if (!is_dir("temp")) {
    mkdir("temp", 0777);
}
//// Absolutní cesta souborového systému pro applice (app)
define('APP_DIR', WWW_DIR . '/app');

// Absolutní cesta souborového systému pro knihovny (libs)
define('LIBS_DIR', WWW_DIR . '/libs');

// Odkomentováním řádku se zobrazí stránka informujísí o údržbě webu
//require APP_DIR . '/templates/maintenance.phtml';
// load bootstrap file
require APP_DIR . '/bootstrap.php';
//require LIBS_DIR . '/VisualPaginator.php';
