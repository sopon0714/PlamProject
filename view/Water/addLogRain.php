<?php
include_once("../../dbConnect.php");
$dim_date;
$dim_farm;
$dim_subfarm;
$dim_owner;
function searchDIMAll($p_date, $p_farm, $p_subfarm)
{
    $p_date = explode('-', $p_date);
    $sql_DimDate = "SELECT ID FROM `dim-time` AS dt WHERE dt.dd = $p_date[0] AND dt.Month = $p_date[1] AND dt.Year2 = $p_date[2]";
    $data1 = selectData($sql_DimDate);
    $GLOBALS['dim_date'] = $data1[1]['ID'];

    $sql_DimFarm = "SELECT ID FROM `dim-farm` AS df WHERE df.dbID = '$p_farm' AND df.IsFarm = 1";
    $data2 = selectData($sql_DimFarm);
    $GLOBALS['dim_farm'] = $data2[1]['ID'];

    $sql_DimSubFarm = "SELECT ID FROM `dim-farm` AS df WHERE df.dbID = '$p_subfarm' AND df.IsFarm = 0";
    $data3 = selectData($sql_DimSubFarm);
    $GLOBALS['dim_subfarm'] = $data3[1]['ID'];

    $sql_DimOwner = "SELECT du.ID FROM `dim-user` AS du JOIN `db-farm` AS df ON df.UFID = du.dbID WHERE  df.FMID = $p_farm";
    $data4 = selectData($sql_DimOwner);
    $GLOBALS['dim_owner'] = $data4[1]['ID'];
}

$date =  $_POST['date'];
$ID_Farm =  $_POST['ID_Farm'];
$ID_SubFarm =  $_POST['ID_SubFarm'];
$StartTime =  $_POST['StartTime'];
$StopTime =  $_POST['StopTime'];
$rank =  $_POST['rank'];
$vol = $_POST['vol'];
$peroid = ($StopTime - $StartTime) / 60.0;
$t = time();




searchDIMAll($date, $ID_Farm, $ID_SubFarm);

$sql_Insert = "INSERT INTO `log-raining`(`ID`, `isDelete`, `Modify`, `LOGloginID`, `DIMdateID`, `StartTime`, `StopTime`, `DIMownerID`, `DIMfarmID`, `DIMsubFID`, `Vol`, `Level`, `Period`) 
                                 VALUES (NULL,0,$t,-1,$dim_date,$StartTime,$StopTime,$dim_owner,$dim_farm,$dim_subfarm,$vol,$rank,$peroid)";
addinsertData($sql_Insert);

// echo "\n\n" . $date . " " . $ID_Farm . " " . $ID_SubFarm . " " . $StartTime . " " . $StopTime . "\n\n";
