<?php
    require      ('../session/session_check_admin.php');
    require_once ('../page/page_template.php');
    require_once ('../lib/groop/src/groop_db_system.php');

    $msg_backup_restore   = '';

    $valid = DBSystem::getUsbStatus();

    if     ($valid == '') {$msg_backup_restore = "Error: USB validity not assigned.";}
    elseif ($valid == 0 ) {$msg_backup_restore = "No USB drive detected.<br>Plug in a USB drive and refresh this page.";}
    elseif ($valid >= 1 ) {$msg_backup_restore = "USB drive detected.";}

    $cmd = '';

    if (isset($_POST['btn_BACKUP'])) {
        $cmd = 'sudo /usr/local/bin/backupdbs.sh';
        exec($cmd);
        $cmd = '';
    }

    if (isset($_POST['btn_RESTORE'])) {
        $cmd = 'sudo /usr/local/bin/restoredbs.sh';
        exec($cmd);
        $cmd = '';
    }



    function printBackupRestoreForm() {
        global $msg_backup_restore;
        global $valid;
        echo '<h2>'.$msg_backup_restore.'</h2>'.PHP_EOL;
        if ($valid >=1) {
            echo '<form class="form" role="form" action="'.$_SERVER['PHP_SELF'].'" method="post">'.PHP_EOL;
            echo '<div><button type = "submit" name = "btn_BACKUP">BACKUP</button></div>'.PHP_EOL;
            echo '<div><button type = "submit" name = "btn_RESTORE">RESTORE</button></div>'.PHP_EOL;
            echo '</form>'.PHP_EOL;
        }
    }

    printHeader();
    printBanner();
    // printNavigation();

?>
<div id="content">
<div><?php printTitle2('SETTINGS:', 'BACKUP/RESTORE') ?></div>
<div class="breadcrumbs spaced">
<a href="settings.php" target="_self">SETTINGS</a>
/
</div>

<hr>
<div><?php printBackupRestoreForm(); ?></div>
</div> <!--/content-->

<?php printFooter() ?>
