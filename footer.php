<?php
$tpl->loadtemplate('footer','templates/footer.tpl.php');
$contents .= $tpl->parse('footer');
echo $contents;
?>