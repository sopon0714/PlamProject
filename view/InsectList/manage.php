<?php

    include_once("../PestList/headManage.php");
    $location = "location:InsectList.php?id=";

    $folderIcon = "../../icon/pest/";
    $folderStyle = "../../picture/pest/insect/style/";
    $folderDanger = "../../picture/pest/insect/danger/";

    $type_pest = "แมลงศัตรูพืช";
    $sql = "SELECT PTID FROM `db-pesttype` WHERE typeTH ='$type_pest'";
    $dptid = selectDataOne($sql)['PTID'];
    // echo 'dptid = '.$dptid.'<br>';

    include_once("../PestList/manage.php");

?>