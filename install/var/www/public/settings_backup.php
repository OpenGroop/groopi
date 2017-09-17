<?php
    include 'session_check.php';
    include 'session_check_admin.php';
    include 'form_backup_restore.php';

    include 'header.php';
    include 'nav_main.php';

?>
<div id="body">
<div> SETTINGS</div>
<div>BACKUP/RESTORE DATA</div>
<div><?php printBackupRestoreForm(); ?></div>
</div> <!--/body-->

<?php include 'footer.php'; ?>
