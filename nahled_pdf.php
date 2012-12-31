<?php

$pdf_exe = htmlspecialchars($_GET['file']);
$slozka = htmlspecialchars($_GET['dir']);
if (empty($_GET['str'])) {
    $str = 0;
} else {
    $str = $_GET['str'];
}
/* Read page 1 */
$im = new imagick($slozka . '/' . $pdf_exe . '[' . $str . ']');
/* Convert to png */
$im->setImageFormat("png");
$imageprops = $im->getImageGeometry();
if ($imageprops['width'] >= 1200 && $imageprops['height'] >= 1200) {
    $im->resizeImage(1260, 1260, imagick::FILTER_QUADRATIC, 1, true);
}
/* Send out */
header("Content-Type: image/png");
echo $im;
?>

