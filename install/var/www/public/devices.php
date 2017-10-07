<?php 
    include 'session_check.php';
    include 'page_template.php';
    include 'devices_list.php';

    // HEADER
    printHeader();

    // BODY
    printBanner();
?>
<div id="content">
<div><?php printTitle('DEVICES'); ?></div>
<div><?php printDevicesList(); ?></div>
</div> <!--/content-->

<?php
    // FOOTER
    printFooter();
?>
