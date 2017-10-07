<?php

    require_once ('lib/groop/src/groop_constants.php');
    require_once ('lib/groop/src/groop_db_user.php');

    $msg      = "Login";

    if (isset($_POST['BTN_LOGIN']) && !empty($_POST['TXT_USERNAME']) && !empty($_POST['TXT_PASSWORD'])) {
        sleep(2);
        $login = DBUser::getLogin($_POST['TXT_USERNAME']);

        if (password_verify($_POST['TXT_PASSWORD'], $login['hash'])) {
            $_SESSION['valid']    = true;
            $_SESSION['timeout']  = time();
            $_SESSION['userid']   = $login['id'];
            $_SESSION['username'] = $login['username'];
            header('Location: devices.php');
            exit;
        } else {
            $msg = "Login failed...";
            error_log("LOGIN ATTEMPT FAILED...user: ".$_POST['TXT_USERNAME']."\r\n", 3, Constants::AUTH_LOG);
        }
    }

    function printLoginForm() {
        global $msg;
        echo '<div><h1>'.$msg.'</h1></div>'.PHP_EOL;
        echo '<form class="form" role ="form" action="'.htmlspecialchars($_SERVER['PHP_SELF']).'" method="post">'.PHP_EOL;
        echo '<div><input  type="text"     name="TXT_USERNAME" placeholder="USERNAME" required></div>'.PHP_EOL;
        echo '<div><input  type="password" name="TXT_PASSWORD" placeholder="PASSWORD" required></div>'.PHP_EOL;
        echo '<div><button type="submit"   name="BTN_LOGIN">Submit</button></div>'.PHP_EOL;
        echo '</form>'.PHP_EOL;
    }
?>







