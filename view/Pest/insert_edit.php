<?php
session_start();
include_once("../../dbConnect.php");
$request = $_POST['request'];
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

function create_directory($path)
{
  $A_path = explode('/', $path);
  $path = "../..";
  foreach ($A_path as $val) {
    $path = $path . "/" . $val;
    if (!file_exists($path)) {
      mkdir($path, 0777, true);
      // echo "\n Insert " . $path . "\n";
    }
  }
}

switch ($request) {
  case 'insert':
    $p_date =  $_POST['p_date'];
    $p_farm =  $_POST['p_farm'];
    $p_subfarm =  $_POST['p_subfarm'];
    $dim_pest =  $_POST['p_pest'];
    $p_note =  $_POST['p_note'];
    $t = time();

    // echo "[ " . $p_date . " " . $p_farm . " " . $p_subfarm . " " . $p_pest . " " . $p_note . " ]";
    searchDIMAll($p_date, $p_farm, $p_subfarm);

    $sql_Insert = "INSERT INTO `log-pestalarm`(`ID`, `isDelete`, `Modify`, `LOGloginID`, `DIMdateID`, `DIMownerID`, `DIMfarmID`, `DIMsubFID`, `DIMpestID`, `Note`, `PICs`) VALUES 
                                              (NULL,0,$t,-1,$dim_date,$dim_owner,$dim_farm,$dim_subfarm,$dim_pest,'$p_note','')";
    $idCurrent = addinsertData($sql_Insert);
    create_directory("picture/activities/pest/$idCurrent");

    $update = "UPDATE `log-pestalarm` SET `PICS` = 'picture/activities/pest/$idCurrent'  WHERE ID = $idCurrent";
    $status = updateData($update);



    if ($status == "OK")
      echo $idCurrent;
    else
      echo -1;
    break;

  case 'edit':
    $p_date =  $_POST['p_date'];
    $p_farm =  $_POST['p_farm'];
    $p_subfarm =  $_POST['p_subfarm'];
    $dim_pest =  $_POST['p_pest'];
    $p_note =  $_POST['p_note'];
    $pestAlarmID = $_POST['pestAlarmID'];
    $t = time();
    // echo "[ " . $p_date . " " . $p_farm . " " . $p_subfarm . " " . $p_rank . " " . $p_pest . " " . $p_note . " ]";  
    searchDIMAll($p_date, $p_farm, $p_subfarm);

    $sql_edit = "UPDATE `log-pestalarm` SET `Modify`=$t,`DIMdateID`=$dim_date,`DIMownerID`=$dim_owner,`DIMfarmID`=$dim_farm,`DIMsubFID`=$dim_subfarm,`DIMpestID`=$dim_pest,`Note`='$p_note' WHERE `ID`=$pestAlarmID";
    $status = updateData($sql_edit);

    if ($status == "OK")
      echo $pestAlarmID;
    else
      echo -1;
    break;
}
