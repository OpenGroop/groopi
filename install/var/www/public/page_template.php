<?php

    function printHeader() {
        echo '<!DOCTYPE html>'.PHP_EOL;
        echo '<html>'.PHP_EOL;
        echo '<head>'.PHP_EOL;
        echo '<title> Groopi </title>'.PHP_EOL;
        echo '<link rel="stylesheet" type="text/css" href="css/style.css" media="all" />'.PHP_EOL;
        echo '</head>'.PHP_EOL;
    }


    function printBanner() {
        echo '<body>'.PHP_EOL;
        echo '<div id="container">'.PHP_EOL;
        echo '<div id="header"><span style="color:#800000">OPEN</span>GRO<span style="color:#800000;">/</span>OP</div>'.PHP_EOL;
        if (isset($_SESSION['valid'])) {
            echo '<div id="nav-main">'.PHP_EOL;
            echo '<div class="nav-main-block"><a href="devices.php"  target="_self">Devices</a></div>'.PHP_EOL;

            if ($_SESSION['userid'] <= 2) {
                echo '<div class="nav-main-block"><a href="settings.php" target="_self">Settings</a></div>'.PHP_EOL;
            }

            echo '<div class="nav-main-block"><a href="logout.php" target="_self">Logout</a></div>'.PHP_EOL;
            echo '</div> <!--/nav-main-->'.PHP_EOL;
        }
    }


    function printTitle($page_title) {
        echo '<div class="title">'.$page_title.'</div>'.PHP_EOL;
    }

    function printTitle2($line1, $line2) {
        echo '<div class="title">'.PHP_EOL;
        echo '<div>' . $line1 . '</div>'.PHP_EOL;
        echo '<div>' . $line2 . '</div>'.PHP_EOL;
        echo '</div>'.PHP_EOL;
    }



    function printFooter() {
        echo '<div id="footer">'.PHP_EOL;
        echo '<div>Powered by Jah!</div>'.PHP_EOL;
        echo '</div>  <!--/footer-->'.PHP_EOL;
        echo '</div>  <!--/container-->'.PHP_EOL;
        echo '</body>'.PHP_EOL;
        echo '</html>'.PHP_EOL;
    }

?>
