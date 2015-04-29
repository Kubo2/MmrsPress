<?php
/**
 *
 * @author Dušan Vala as rellik
 * http://mmrs.rellik.eu
 * 
 */
$slozka = '../image/';
$vypis = opendir($slozka);
while (false !== ($file = readdir($vypis))) {
    if ($file != "." && $file != "..") {
        $finfo = explode('.', $file);
        $fileSuffix = array_pop($finfo);
        $array = array("jpg", "png", "gif");

        if (in_array($fileSuffix, $array)) {
            echo "<img src='" . $slozka . $file . "' alt='" . $file . "' width='100' />\n <a href='file.php?del_img=ok&amp;image=" . $file . "'>Smazat</a><br /> <hr />\n";
        } else {
            echo "<a href='" . $slozka . $file . "'>" . $file . "</a> <a href='file.php?del_img=ok&amp;image=" . $file . "'>Smazat</a><br /><hr />\n\t\t\n";
        }
    }
}
closedir($vypis);

$del_img = $_GET['del_img'];
$del_file = $_GET['image'];
if ($del_img == "ok") {
    if (file_exists($slozka . $del_file)) {
        unlink($slozka . $del_file);
        header('Location: file.php');
    }
}
?>