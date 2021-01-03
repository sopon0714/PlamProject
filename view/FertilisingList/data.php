<?php

require_once("../../dbConnect.php");
require_once("../../set-log-login.php");
require_once("../../query/query.php");
session_start();
date_default_timezone_set('Asia/Bangkok');
$myConDB = connectDB();

$select_id = $_POST["select_id"] ?? '';
$result = $_POST["result"] ?? '';
$point_id = $_POST["point_id"] ?? '';

// echo $select_id;
// echo $result;
// echo $point_id;

if ($result == 'distrinct' || $result == 'distrinctSF') {
    if ($select_id == 0) {
        echo "<option selected value=0>เลือกอำเภอ</option>";
    }
    $sql = "SELECT * FROM `db-distrinct` WHERE `AD1ID` = '$select_id' ORDER BY `db-distrinct`.`Distrinct`  ASC ";
    $result = $myConDB->prepare($sql);
    $result->execute();
    // if($result =='s_distrinct'){
    if ($point_id == '') {
        echo "<option selected value=0 disabled=\"\">เลือกอำเภอ</option>";
    }
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value =" . $row['AD2ID'] . " ";
        if ($row['AD2ID'] == $point_id && $result != 's_distrinct') {
            echo " selected='selected' ";
        }
        echo ">" . $row['Distrinct'] . "</option>";
    }
}
if ($result == 's_distrinct') {
    if ($select_id == 0) {
        echo "<option selected value=0>เลือกอำเภอ</option>";
    }
    $sql = "SELECT * FROM `db-distrinct` WHERE `AD1ID` = '$select_id' ORDER BY `db-distrinct`.`Distrinct`  ASC ";
    $result = $myConDB->prepare($sql);
    $result->execute();
    // if($result =='s_distrinct'){
    if ($point_id == '') {
        echo "<option selected value=0 >เลือกอำเภอ</option>";
    }
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value =" . $row['AD2ID'] . " ";
        if ($row['AD2ID'] == $point_id && $result != 's_distrinct') {
            echo " selected='selected' ";
        }
        echo ">" . $row['Distrinct'] . "</option>";
    }
}
if ($result == 'subdistrinct'  || $result == 'subdistrinctSF') {
    if ($select_id == 0) {
        echo "<option selected value=0 disabled=\"\">เลือกตำบล</option>";
    }
    $sql = "SELECT * FROM `db-subdistrinct` WHERE `AD2ID` = '$select_id' ORDER BY `db-subdistrinct`.`subDistrinct`  ASC";
    $result = $myConDB->prepare($sql);
    $result->execute();
    if ($point_id == '') {
        echo "<option selected value=0 disabled=\"\">เลือกตำบล</option>";
    }

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value =" . $row['AD3ID'] . " ";
        if ($row['AD3ID'] == $point_id) {
            echo " selected";
        }
        echo ">" . $row['subDistrinct'] . "</option>";
    }
}
if ($result == 'e_province') {
    $sql = "SELECT * FROM `db-province` ORDER BY `db-province`.`Province`  ASC";
    $result = $myConDB->prepare($sql);
    $result->execute();
    if ($point_id == '') {
        echo "<option selected value=0>เลือกจังหวัด</option>";
    }
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value =" . $row['AD1ID'] . " ";
        if ($row['AD1ID'] == $point_id) {
            echo " selected";
        }
        echo ">" . $row['Province'] . "</option>";
    }
}
if ($result == 'chartVolNutr') {
    $year = $_POST['year'];
    $fsid = $_POST['FSID'];
    $DATAINFO = [];
    $DATAINFO['speedData'] = [];
    $DATAINFO['Vol'] = [];
    $DATAINFO['diff'] = [];
    $DATA = getInfoNutr();
    $text = "";
    for ($i = 1; $i <= $DATA[0]['numrow']; $i++) {
        array_push($DATAINFO['speedData'], "{$DATA[$i]['Name']}({$DATA[$i]['Unit']})");
        $diff = 0;
        $DATAVOL = getSumVolFertilising($fsid, $year, $DATA[$i]['NID']);
        if ($DATAVOL[0]['numrow'] != 0) {
            $Vol = $DATAVOL[1]['sumVol'];
        } else {
            $Vol = 0;
        }
        $Voluse = getVolUseFertilising($fsid, $DATA[$i]['NID'], $year);
        if ($Vol < $Voluse) {
            $diff = round($Voluse - $Vol, 2);
        }
        array_push($DATAINFO['Vol'], (float)$Vol);
        array_push($DATAINFO['diff'], (float)$diff);
    }
    $DATAINFO['numrow'] = $DATA[0]['numrow'];
    echo json_encode($DATAINFO);
}
if ($result == 'loadDATACal') {
    $year = $_POST['year'];
    $fsid = $_POST['FSID'];
    $DATAINFO = [];

    $DATA = getInfoNutr();
    for ($i = 1; $i <= $DATA[0]['numrow']; $i++) {

        $diff = 0;
        $DATAVOL = getSumVolFertilising($fsid, $year, $DATA[$i]['NID']);
        if ($DATAVOL[0]['numrow'] != 0) {
            $Vol = $DATAVOL[1]['sumVol'];
        } else {
            $Vol = 0;
        }
        $Voluse = getVolUseFertilising($fsid, $DATA[$i]['NID'], $year);
        if ($Vol < $Voluse) {
            $diff = round($Voluse - $Vol, 2);
        }
        $DATAINFO['Nutr'][$DATA[$i]['NID']]['NID'] = $DATA[$i]['NID'];
        $DATAINFO['Nutr'][$DATA[$i]['NID']]['Unit'] = $DATA[$i]['Unit'];
        $DATAINFO['Nutr'][$DATA[$i]['NID']]['UnitNum'] = $DATA[$i]['UnitNum'];
        $DATAINFO['Nutr'][$DATA[$i]['NID']]['diff'] = $diff;
    }
    $sql = "SELECT * FROM `log-fertilizercomposition` 
    INNER JOIN `log-fertilizer` ON `log-fertilizer`.`ID` =`log-fertilizercomposition`.`FerID`
    WHERE `log-fertilizer`.`isDelete`=0";
    $DATA = selectData($sql);
    for ($i = 1; $i <= $DATA[0]['numrow']; $i++) {
        $DATAINFO['Fer'][$DATA[$i]['FerID']][$DATA[$i]['NID']]['Percent'] = $DATA[$i]['Percent'] / 100.00;
    }
    echo json_encode($DATAINFO);
}
