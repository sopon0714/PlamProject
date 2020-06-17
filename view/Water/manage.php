<?php
require_once("../../dbConnect.php");
require_once("../../set-log-login.php");
require_once("../../query/query.php");
session_start();
date_default_timezone_set('Asia/Bangkok');
?>

<?php
$action  = $_POST['action'] ?? "";
switch ($action) {
    case 'deleteLog';
        $logid = $_POST['logid'];
        $TYPEP = $_POST['typeid'];
        if ($TYPEP == 3) {
            $name = "`log-raining`";
        } else {
            $name = "`log-watering`";
        }
        $sql = "UPDATE $name SET `isDelete` = '1' WHERE $name.`ID` = $logid";
        updateData($sql);
        break;
    case 'setSelectFarm';
        $date = $_POST['date'];
        $INFODATA = getFarmByModify(strtotime($date));
        $html = " <option value=\"0\" selected>เลือกสวน</option>";
        for ($i = 1; $i < count($INFODATA); $i++) {
            $html .= " <option value=\"{$INFODATA[$i]['DIMFID']}\" selected>{$INFODATA[$i]['Name']}</option>";
        }
        echo $html;
        break;
    case 'setSelectSubfarm';
        $FIMD = $_POST['FIMD'];
        $date = $_POST['date'];
        $INFODATA = getsubFarmByModify2($FIMD, strtotime($date));
        $html = " <option value=\"0\" selected>เลือกแปลง</option>";
        for ($i = 1; $i < count($INFODATA); $i++) {
            $html .= " <option value=\"{$INFODATA[$i]['DIMFSID']}\" selected>{$INFODATA[$i]['Name']}</option>";
        }
        echo $html;
        break;
    case 'AddRain';
        $FSID = $_POST['FSID'] ?? "";
        $TypeDetail = $_POST['TypeDetail'] ?? "";
        $dateRain = $_POST['dateRain'];
        $dimFarmIDRian = $_POST['FarmIDRian'];
        $dimSubFarmIDRian = $_POST['SubFarmIDRian'];
        $timeStratRian = $_POST['timeStratRian'];
        $timeEndRian = $_POST['timeEndRian'];
        $Type = $_POST['Type'];
        $to_time = strtotime($dateRain . " " . $timeEndRian);
        $from_time = strtotime($dateRain . " " . $timeStratRian);
        $min = round(abs($to_time - $from_time) / 60, 2);
        $LOG_LOGIN = $_SESSION[md5('LOG_LOGIN')];
        $DIMDATE = getDIMDate($dateRain);
        $dimOwnerID = getDIMOwner($dimFarmIDRian);
        $time = time();
        if ($Type == 1) {
            $rankRain = $_POST['rankRain'];
            $hours = round(abs($to_time - $from_time) / 60 / 60, 2);
            $Vol = round(($rankRain * $hours) / 24.0, 2);
            if ($rankRain == "5.00") {
                $rank = "'1'";
            } else  if ($rankRain == "22.50") {
                $rank = "'2'";
            } else  if ($rankRain == "62.50") {
                $rank = "'3'";
            } else  if ($rankRain == "110.00") {
                $rank = "'4'";
            } else {
                $rank = "NULL";
            }
        } else {
            $rainVol = $_POST['rainVol'];
            $Vol =  $rainVol;
            $rank = "NULL";
        }
        $sql = "INSERT INTO `log-raining` (`ID`, `isDelete`, `Modify`, `LOGloginID`,
                `DIMdateID`, `StartTime`, `StopTime`, `DIMownerID`, `DIMfarmID`, `DIMsubFID`, `Vol`, `Level`, `Period`) 
                VALUES (NULL, '0', '$time', '{$LOG_LOGIN[1]['ID']}', '{$DIMDATE[1]['ID']}', '$from_time',
                '$to_time', '$dimOwnerID', '$dimFarmIDRian', '$dimSubFarmIDRian', '$Vol', $rank, '$min')";
        addinsertData($sql);
        echo "555";
        if ($TypeDetail == "Detail") {
            header("location:WaterDetail.php?FSID=$FSID&Active=3");
        } else {
            header("location:Water.php");
        }
        break;
    case 'AddWater';
        $FSID = $_POST['FSID'] ?? "";
        $TypeDetail = $_POST['TypeDetail'] ?? "";
        $dateWater = $_POST['dateWater'];
        $dimFarmIDWater = $_POST['FarmIDWater'];
        $dimSubFarmIDWater = $_POST['SubFarmIDWater'];
        $timeStratWater = $_POST['timeStratWater'];
        $timeEndWater = $_POST['timeEndWater'];
        $to_time = strtotime($dateWater . " " . $timeEndWater);
        $from_time = strtotime($dateWater . " " . $timeStratWater);
        $min = round(abs($to_time - $from_time) / 60, 2);
        $LOG_LOGIN = $_SESSION[md5('LOG_LOGIN')];
        $DIMDATE = getDIMDate($dateWater);
        $dimOwnerID = getDIMOwner($dimFarmIDWater);
        $time = time();
        $Vol = $_POST['waterVol'];
        $sql = "INSERT INTO `log-watering` (`ID`, `isDelete`, `Modify`, `LOGloginID`,
               `DIMdateID`, `StartTime`, `StopTime`, `DIMownerID`, `DIMfarmID`, `DIMsubFID`, `Vol`, `Period`)
                VALUES (NULL, '0', '$time', '{$LOG_LOGIN[1]['ID']}', '{$DIMDATE[1]['ID']}', '$from_time', '$to_time',
                '$dimOwnerID', '$dimFarmIDWater', ' $dimSubFarmIDWater', '$Vol', '$min')";
        addinsertData($sql);
        if ($TypeDetail == "Detail") {
            header("location:WaterDetail.php?FSID=$FSID&Active=4");
        } else {
            header("location:Water.php?active=2");
        }
        break;
}
function getDIMOwner($dim_farm)
{
    $sql = "SELECT `log-farm`.`DIMownerID` FROM(
      SELECT MAX(`log-farm`.`ID`)  AS ID FROM `log-farm` 
      WHERE `log-farm`.`DIMfarmID`  = '$dim_farm')AS t1
      JOIN `log-farm` ON `log-farm`.`ID` = t1.ID";

    $data = selectData($sql)[1]['DIMownerID'];
    return $data;
}
