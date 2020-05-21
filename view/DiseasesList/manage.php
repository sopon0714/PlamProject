<?php

    include_once("../PestList/headManage.php");
    $location = "location:DiseasesList.php?id=";

    $folderIcon = "../../icon/pest/";
    $folderStyle = "../../picture/pest/disease/style/";
    $folderDanger = "../../picture/pest/disease/danger/";

    $type_pest = "โรคพืช";
    $sql = "SELECT PTID FROM `db-pesttype` WHERE typeTH ='$type_pest'";
    $dptid = selectDataOne($sql)['PTID'];
    // echo 'dptid = '.$dptid.'<br>';

    include_once("../PestList/manage.php");

?>