<?php 
    require      ('../session/session_check.php');
    require_once ('../page/page_template.php');
    include      ('list_devices.php');

    // HEADER
    printHeader();

    // BODY
    printBanner();
  //  printNavigation();
?>

<div id="content">
<div><?php printTitle('SENSORS'); ?></div>
<hr>
<div><?php printDevicesList(); ?></div>
</div> <!--/content-->
<?php
    // FOOTER
    printFooter();
?>
