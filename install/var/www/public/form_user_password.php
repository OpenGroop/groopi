<?php 
require_once ('lib/groop/src/groop_db_user.php');

    $msg_pw ='';

    if (isset($_POST['BTN_PW']) && !empty($_POST['TXT_PW_1']) && !empty($_POST['TXT_PW_2'])) {

        sleep(2);

        if ($_POST['TXT_PW_1'] == $_POST['TXT_PW_2']) {
            DBUser::updateHash($_GET['id'], $_POST['TXT_PW_1']);
            header('Location: settings_users.php');
            exit;
        } else {
            $msg_pw = "Failed! New passwords do not match.";
        }
    }

    function printPasswordForm($userid) {
        global $msg_pw;
        $action = $_SERVER['PHP_SELF'] .'?id='.$userid;
        echo '<form role="form" action="'.htmlspecialchars($action).'" method="post">'.PHP_EOL;
        echo '<div>CHANGE PASSWORD</div>'.PHP_EOL;
        echo '<div><input type="password" name="TXT_PW_1" placeholder="NEW PASSWORD" required/></div>'.PHP_EOL;
        echo '<div><input type="password" name="TXT_PW_2" placeholder="CONFIRM PASSWORD" required/></div>'.PHP_EOL;
        echo '<div><button type="submit" name="BTN_PW">Submit</button>'.PHP_EOL;
        echo '<div>'.$msg_pw.'</div>'.PHP_EOL;
        echo '</form>'.PHP_EOL;
    }


?>