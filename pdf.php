<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="robots" content="all, follow">
        <title>Prohlížeč PDF dokumentu</title>
        <link rel="stylesheet" href="css/pdf.css" type="text/css">
        <link rel="shortcut icon" href="/images/web.gif" type="image/x-icon" />
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script> 
        <script type="text/javascript" src="js/pdf.js"></script> 
        <style type="text/css">
            /* Style the second URL with a red border */
            #test-gdocsviewer {
                border: 5px red solid;
                padding: 20px;
                width: 100%;
                background: #ccc;
                text-align: center;
            }
            /* Style all gdocsviewer containers */
            .gdocsviewer {
                margin:10px;
            }
        </style>
        <script type="text/javascript"> 
            /*<![CDATA[*/
            $(document).ready(function() {
                $('a.embed').gdocsViewer({width: 960, height: 750});
                $('#embedURL').gdocsViewer();
            });
            /*]]>*/
        </script> 
    </head>
    <body>
        <div id="hlavicka"> <!-- hlavicka -->
            <h1><img src="images/pdf.png" alt=""> Prohlížeč dokumentů</h1>
        </div> <!-- hlavicka konec -->
        <div id="text"> <!-- text -->
            <ul>
                <li><a href="javascript:self.history.back();" class="back">Zpět na stránky</a></li>
                <?php

                function get_path_info() {
                    $B = substr(__FILE__, 0, strrpos(__FILE__, '/'));
                    $A = substr($_SERVER['DOCUMENT_ROOT'], strrpos($_SERVER['DOCUMENT_ROOT'], $_SERVER['PHP_SELF']));
                    $C = substr($B, strlen($A));
                    $posconf = strlen($C);
                    $D = substr($C, 1, $posconf);
                    if (empty($D)) {
                        return 'http://' . $_SERVER['SERVER_NAME'] . '/';
                    } else {
                        return 'http://' . $_SERVER['SERVER_NAME'] . '/' . $D . '/';
                    }
                }

                $file = htmlspecialchars($_GET['file']);
                $slozka = trim(htmlspecialchars($_GET['dir']));
                echo '<li><a href="' . get_path_info() . $slozka . '/' . $file . '" class="embed back">Stáhnout soubor</a></li>';
                ?>
            </ul>
            <p>
                2005-<?php echo date('Y'); ?>
                © <a href="http://mmrspress.eu">MmrsPress</a> by 
                <a href="mailto:rellik@rellik.eu">Rellik</a> 
            </p> 
        </div> <!-- text konec -->
    </body>
</html>