<?php
    require      ('../session/session_check_admin.php');
    require_once ('../page/page_template.php');
    require_once ('../lib/groop/src/groop_db_user.php');

    $msg = '';

    if (isset($_POST['BTN_USER_ADD']) ) {
        if ($_POST['TXT_PW_1'] != $_POST['TXT_PW_2']) { $msg = "Passwords do not match..";}
        else {
            $status = DBUser::add($_POST['TXT_USER'],$_POST['TXT_PW_1']);
            if ($status == -1 || $status == -2) { $msg = "There seems to be a problem..(" . $status .")"; }
            if ($status == -3) { $msg = "Username already in use.\nChoose another username."; }
            if ($status == 0) {
                header('HTTP/1.1 303');
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
            }
        }
    }

    printHeader();
    printBanner();
    // printNavigation();

?>
<div id="content">
<div class="breadcrumbs spaced">
<a href="settings.php" target="_self">SETTINGS</a>
/
</div>
<div><?php printTitle('SETTINGS:USERS') ?></div>

<hr>

<div class="map-title">EDIT USER</div>
<div><?php include 'list_users.php'; ?></div>
<hr>
<div>
<form class="form" role="form" action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
<div class="map-title">ADD USER</div>
<div class="map">
    <div class="map-key">Username:</div>
    <div class="map-value"><input type="text" name="TXT_USER" required/></div>
</div>

<div class="map">
    <div class="map-key">Password:</div>
    <div class="map-value"><input type="password" name="TXT_PW_1" required /></div>
</div>

<div class="map">
    <div class="map-key">Password:</div>
    <div class="map-value"><input type="password" name="TXT_PW_2" required /></div>
</div>

<div><button type="submit"  name="BTN_USER_ADD">SUBMIT</button></div>
<div><?php echo $msg ?></div>
</form>
</div>
<hr>
<div class="settings-subheading">
    <h3>Managing users</h3>
    <p>It is highly advised to change the default password set for the user <i>'admin'</i>, especially so for those who plan on connecting their RPi to a network. But be warned that there is currently no facility to reset the <i>admin</i> password. So don't outsmart yourself by choosing a complicated password. The goal here is to change the password from the default password supplied, which is a publicly disclosed password.</p>
    <p>New users may be added to access the OpenGro/Op console.</p>
    <p>Only the user <i>'admin'</i> has access to setting pages. All users other than <i>'admin'</i> will only be able to view data.</p>
    <p>The user <i>'admin'</i> cannot be deleted.</p>
</div>
</div> <!--/content-->

<?php printFooter() ?>
