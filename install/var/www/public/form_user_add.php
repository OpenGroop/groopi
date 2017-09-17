<?php
    require_once ('lib/groop/src/groop_db_user.php');

    $msg = '';

    if (isset($_POST['BTN_USER_ADD']) ) {
        if ($_POST['TXT_PW_1'] == $_POST['TXT_PW_2']) {
            DBUser::add($_POST['TXT_USER'],$_POST['TXT_PW_1']);
            header('Location: settings_users.php');
            exit;
        } else {
            $msg = "PASSWORDS DO NOT MATCH";
        }
    }
    
    function printForm() {
        global $msg;
        echo '<form role="form" action="'.htmlspecialchars($_SERVER['PHP_SELF']).'" method="post">'.PHP_EOL;
        echo '<div><input type="text" name="TXT_USER" placeholder="ENTER USERNAME" required/></div>'.PHP_EOL;
        echo '<div><input type="password" name="TXT_PW_1" placeholder="ENTER PASSWORD" required /></div>'.PHP_EOL;
        echo '<div><input type="password" name="TXT_PW_2" placeholder="CONFIRM PASSWORD" required /></div>'.PHP_EOL;
        echo '<div><button type="submit" name="BTN_USER_ADD">SUBMIT</button></div>'.PHP_EOL;
        echo '<div>'.$msg.'</div>'.PHP_EOL;
        echo '</form>'.PHP_EOL;
    }
?>