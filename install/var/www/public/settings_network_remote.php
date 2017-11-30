<?php 
    include 'session_check.php';
    include 'session_check_admin.php';
    include 'page_template.php';

//    $ip_addr = exec('curl -s https://www.nancoo.org/ip-address.php | grep ip_address | sed "s/<[^>]*>//g"');

    // HEADER
    printHeader();

    // BODY
    printBanner();
?>
<div id="content">
<div><?php printTitle('REMOTE NETWORK'); ?></div>

</div> <!--/content-->

<?php
    // FOOTER
    printFooter();
?>