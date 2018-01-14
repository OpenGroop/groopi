<?php

    require_once ('../lib/groop/src/groop_constants.php');
    require_once ('../lib/groop/src/groop_db_system.php');
    require_once ('../lib/groop/src/groop_db_user.php');

    function login($username, $password) {
        sleep(2);
        $status = '';
        $login = DBUser::getLogin($username);

        if ( $login['status']==0 ) {

            if (password_verify($password, $login['hash'])) {
                $_SESSION['valid']    = true;
                $_SESSION['timeout']  = time();
                $_SESSION['userid']   = $login['id'];
                $_SESSION['username'] = $login['username'];


            } else {
                error_log("LOGIN ATTEMPT FAILED...user: ".$_POST['TXT_USERNAME']."\r\n", 3, Constants::AUTH_LOG);
                $status = -3;
            }

        } else {
            $status = $login['status'];
        }

        return $exit;
    }
?>







