<?php 
    include 'session_check.php';
    include 'session_check_admin.php';
    include 'page_template.php';


    // HEADER
    printHeader();

    // BODY
    printBanner();
?>
<div id="content">
<div><?php printTitle('AP NETWORK'); ?></div>
<div></div>
</div> <!--/content-->

<?php
    // FOOTER
    printFooter();
?>