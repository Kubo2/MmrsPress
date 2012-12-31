<!DOCTYPE HTML>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="robots" content="all, follow">
    <meta name="author" content="Design a kod: Rellik ; mailto:rellik1@seznam.cz">
    <title>Prohlížeč PDF dokumentu</title>
    <link rel="stylesheet" href="css/pdf.css" type="text/css">
    <script type="text/javascript" src="js/jquery-1.8.0.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.8.23.custom.min.js"></script>
    <script>
        $(document).ready(function() {
            //Text Zoom 
            $('.controls a').click(function(){
                var pdf = $('#pdf');
                var currFontSize = pdf.css('width');
                var finalNum = parseFloat(currFontSize, 10);
                var stringEnding = currFontSize.slice(-2);
                if(this.id == 'large') {
                    finalNum *= 1.2;
                }
                else if (this.id == 'small'){
                    finalNum /=1.2;
                }
                pdf.css('width', finalNum + stringEnding);
            });
        });
    </script>
</head>

<body>
    <div id="hlavicka"> <!-- hlavicka -->
        <h1><img src="images/pdf.png" alt=""> Prohlížeč PDF dokumentu</h1>
    </div> <!-- hlavicka konec -->
    <div id="menu"> <!-- leve menu -->
        <ul id="textzoom">
            <li class="controls">
                <a href="#" id="large" class="bigger" title="zoom +"><img src="images/plus.png" alt="Zvětšit"></a>
                <a href="#" id="small" class="smaller" title="zoom -"><img src="images/minus.png" alt="Zmenšit"></a> 
            </li>
        </ul>
        <ul id="mainMenu">
            <?php
            $file = htmlspecialchars($_GET['file']);
            $page = htmlspecialchars($_GET['page']);
            $slozka = trim(htmlspecialchars($_GET['dir']));
            echo '<a href="' . $slozka . '/' . $file . '" class="back">Stáhnout soubor</a>';
            ?>
            <a href="./" class="back">Zpět na stránky</a>
            <p><strong>Stránky dokumentu:</strong></p>

            <?php
            if (!$fp = @fopen($slozka . '/' . $file, "r")) {
                echo 'failed opening file ' . $_REQUEST['file'];
            } else {
                $max = 0;
                while (!feof($fp)) {
                    $line = fgets($fp, 255);
                    if (preg_match('/\/Count [0-9]+/', $line, $matches)) {
                        preg_match('/[0-9]+/', $matches[0], $matches2);
                        if ($max < $matches2[0])
                            $max = $matches2[0];
                    }
                }
                fclose($fp);
            }
            for ($i = 0; $i <= $max - 1; $i++) {
                $b = $i + 1;
                if ($i == $page) {
                    echo '<li><a href="?dir=' . $slozka . '&file=' . $file . '&page=' . $i . '" class="aktiv">Strana ' . $b . '</a></li>' . "\n";
                } else {
                    echo '<li><a href="?dir=' . $slozka . '&file=' . $file . '&page=' . $i . '">Strana ' . $b . '</a></li>' . "\n";
                }
            }
            ?>
        </ul>
    </div> <!-- leve menu konec -->

    <div id="text"> <!-- text -->
        <img src="nahled_pdf.php?<?php echo 'dir=' . $slozka . '&file=' . $file . '&str=' . $page; ?>" id="pdf" alt="">
        <p>Pokud se soubor nezobrazil, zkuste obnovit stránku pomocí klávesy F5.</p>
    </div> <!-- text konec -->
</body>
</html>