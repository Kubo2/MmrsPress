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
                <li><a href="./" class="back">Zpět na stránky</a></li>
            <?php
            $file = htmlspecialchars($_GET['file']);
            $page = htmlspecialchars($_GET['page']);
            $slozka = trim(htmlspecialchars($_GET['dir']));
            echo '<li><a href="http://'.$_SERVER['SERVER_NAME'].'/' . $slozka . '/' . $file . '" class="embed back">Stáhnout soubor</a></li>';
            ?>
            </ul>
        </div> <!-- text konec -->
    </body>
</html>