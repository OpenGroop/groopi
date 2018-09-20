<?php

    function printHeader() {
        echo '<!DOCTYPE html>'.PHP_EOL;
        echo '<html>'.PHP_EOL;
        echo '<head>'.PHP_EOL;
        echo '<title> Groopi </title>'.PHP_EOL;
        echo '<link rel="stylesheet" type="text/css" href="css/style.css?v3" media="all" />'.PHP_EOL;
        echo '</head>'.PHP_EOL;
        echo '<body>'.PHP_EOL;
        echo '<div id="container">'.PHP_EOL;
    }

    function printHeaderLogin() {
        echo '<!DOCTYPE html>'.PHP_EOL;
        echo '<html>'.PHP_EOL;
        echo '<head>'.PHP_EOL;
        echo '<title> Log in.. </title>'.PHP_EOL;
        echo '<link rel="stylesheet" type="text/css" href="css/login.css?v=6333" media="all" />'.PHP_EOL;
        echo '</head>'.PHP_EOL;
        echo '<body>'.PHP_EOL;
        echo '<div id="container">'.PHP_EOL;
    }


    function printNavigation() {
        if (isset($_SESSION['valid'])) {
            echo '<div id="nav-main">'.PHP_EOL;
            echo '<div class="nav-main-block"><a href="devices.php"  target="_self">Sensors</a></div>'.PHP_EOL;

            if ($_SESSION['userid'] == 1) {
                echo '<div class="nav-main-block"><a href="settings.php" target="_self">Settings</a></div>'.PHP_EOL;
            }

            echo '<div class="nav-main-block"><a href="support.php" target="_self">Support</a></div>'.PHP_EOL;
            echo '<div class="nav-main-block"><a href="logout.php" target="_self">Logout</a></div>'.PHP_EOL;
            echo '<hr>'.PHP_EOL;
            echo '</div> <!--/#nav-main-->'.PHP_EOL;
        }
    }

    function printBanner() {
    	echo '<header id="header">'.PHP_EOL;
        echo '<span id="open">Open</span><span id="gro">Gro</span><span id="slsh">/</span><span id="op">Op</span>'.PHP_EOL;
        printNavigation();
        echo '</header>'.PHP_EOL;
    }



    function printLogout() {
        if (isset($_SESSION['valid'])) {
            echo '<div id="nav-main">'.PHP_EOL;
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
        echo '<footer id="footer" class="spaced">'.PHP_EOL;
	echo '<hr>'.PHP_EOL;
#        echo '<div>www.opengroop.org <img src="media/globe.svg" alt="globe.svg"></div>'.PHP_EOL;
        echo '<div>support@opengroop.org <img src="media/mail.svg" alt="mail.svg"></div>'.PHP_EOL;
        echo '<div>@OpenGroop <img src="media/twit.svg" alt="twit.svg"></div>'.PHP_EOL;
        echo '<div>v0.2.9</div>'.PHP_EOL;
        echo '</footer>  <!--/footer-->'.PHP_EOL;
        echo '</div>  <!--/container-->'.PHP_EOL;
        echo '</body>'.PHP_EOL;
        echo '</html>'.PHP_EOL;
    }

    function printFooterLogin() {
        echo '</div>'.PHP_EOL;
        echo '</body>'.PHP_EOL;
        echo '</html>'.PHP_EOL;
    }

?>
