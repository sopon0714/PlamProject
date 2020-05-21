<?php

    include_once("../PestList/headManage.php");
    $location = "location:WeedList.php?id=";

    $folderIcon = "../../icon/pest/";
    $folderStyle = "../../picture/pest/weed/style/";
    $folderDanger = "../../picture/pest/weed/danger/";

    $type_pest = "วัชพืช";
    $sql = "SELECT PTID FROM `db-pesttype` WHERE typeTH ='$type_pest'";
    $dptid = selectDataOne($sql)['PTID'];
    // echo 'dptid = '.$dptid.'<br>';

    include_once("../PestList/manage.php");

?>