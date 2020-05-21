<?php

    include_once("../PestList/headManage.php");
    $location = "location:OtherPestList.php?id=";

    $folderIcon = "../../icon/pest/";
    $folderStyle = "../../picture/pest/other/style/";
    $folderDanger = "../../picture/pest/other/danger/";

    $type_pest = "ศัตรูพืชอื่นๆ";
    $sql = "SELECT PTID FROM `db-pesttype` WHERE typeTH ='$type_pest'";
    $dptid = selectDataOne($sql)['PTID'];
    // echo 'dptid = '.$dptid.'<br>';

    include_once("../PestList/manage.php");

?>