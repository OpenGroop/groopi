<?php
    session_start();
    require_once ('../lib/groop/src/groop_db_user.php');
    require_once ('../page/page_template.php');

    $msg = '';

    if ( isset($_POST['BTN_LOGIN']) ) {

        sleep(2);
        $login = DBUser::getLogin($_POST['TXT_USERNAME']);

        switch ($login['status']) {
            case Constants::DB_ACTION_OK:
                if (password_verify($_POST['TXT_PASSWORD'], $login['hash'])) {
                    exec('sudo hwclock-set.sh ' . $_POST['CLIENT_TIME']);
                    $_SESSION['valid']    = true;
                    $_SESSION['timeout']  = time();
                    $_SESSION['userid']   = $login['id'];
                    $_SESSION['username'] = $login['username'];
                    header('HTTP/1.1 303');
                    header('Location: https://' . $_SERVER['HTTP_HOST'] . '/devices.php');
                    exit;
                } else {
                    $msg = "Login failed..try again";
                }
                break;

            case Constants::DB_CONN_ERR:
                $msg = "There seems to be a problem..";
                break;

            case Constants::DB_QUERY_ERR:
                $msg = "Login failed..try again";
                break;
        }
    }
    printHeaderLogin();
//    printBanner();
?>

    <form role ="form" action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
        <div>Log in..</div>
        <div><input  type="text"     name="TXT_USERNAME" placeholder="USERNAME" required /></div>
        <div><input  type="password" name="TXT_PASSWORD" placeholder="PASSWORD" required /></div>
        <div><input type="hidden" id="client_time" name="CLIENT_TIME" value="" /></div>
        <div><button type="submit" id="btn_login" name="BTN_LOGIN">Submit</button></div>
        <div><?php echo $msg; ?></div>
    </form>

<script>
    document.getElementById('btn_login').onclick = function() {
        date = new Date();
        document.getElementById('client_time').value = date.toISOString();
    }
</script>

<?php printFooterLogin() ?>
