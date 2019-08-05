<?php
require_once("../../modelo/oa/moOA.php");
require_once("../../modelo/meta/moMeta.php");

$oa = new oa(0);
$meta = new meta(0,'');

$oa_url = $_REQUEST['oa_url'];
$oa_id = $oa->getId($oa_url);

if (!$meta->delete($oa_id)){
    echo 0;
}
else {
    if(!$oa->delete($oa_url)){
        echo 0;
    }
    else echo 1;
}
?>