<?php
    include 'session_check.php';
    include 'session_check_admin.php';
    require_once ('lib/groop/src/groop_device_register.php');
    include 'page_template_ds.php';
    include 'form_device_alias.php';

    $register = new DeviceRegister($_GET['id']);

    // HEADER
    printHeader();

    // BODY
    printBanner();
?>
<div id="content">
<div><?php printTitle($register->getAlias()) ?></div>
<div><?php printDSHeader($register); ?></div>
<div><?php printAliasForm($register->getId()); ?></div></br>
<div><?php printDSBacklink($register) ?></div>
</div> <!--/content-->

<?php
    // FOOTER
    printFooter();
?>
