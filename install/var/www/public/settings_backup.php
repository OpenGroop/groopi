<?php
    include 'session_check.php';
    include 'session_check_admin.php';
    include 'page_template.php';
    include 'form_backup_restore.php';

    printHeader();
    printBanner();

?>
<div id="content">
<div><?php printTitle2('SETTINGS:', 'BACKUP/RESTORE') ?></div>
<div><?php printBackupRestoreForm(); ?></div>
</div> <!--/content-->

<?php printFooter() ?>
