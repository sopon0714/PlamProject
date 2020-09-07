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
        ////////// delete fact-watering drying
        $sql = "SELECT $name.*, `dim-time`.`Date`,`dim-farm`.`dbID` AS FSID FROM $name
        INNER JOIN  `dim-time` ON `dim-time`.`ID` =$name.`DIMdateID`
        INNER JOIN `dim-farm` ON `dim-farm`.`ID` = $name.`DIMsubFID`
        WHERE $name.`ID` = $logid";
        $INFOLOG = selectData($sql);
        $date = $INFOLOG[1]['Date'];
        $min = $INFOLOG[1]['Period'];
        $FSID = $INFOLOG[1]['FSID'];
        $time = time();
        $LOG_LOGIN = $_SESSION[md5('LOG_LOGIN')];

        $sql = "SELECT `fact-watering`.* FROM `fact-watering`
            INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `fact-watering`.`DIMsubFID`
             INNER JOIN  `dim-time` ON `dim-time`.`ID` =`fact-watering`.`DIMdateID`
            WHERE `dim-farm`.`dbID` = $FSID  AND `dim-time`.`Date` = '$date'";
        $INFOFACTWATERING = selectData($sql);
        if ($INFOFACTWATERING[1]['TotalPeriod'] - $min > 0) {
            if ($TYPEP == 3) {
                $RainPeriod =  $INFOFACTWATERING[1]['RainPeriod'] - $min;
                $TotalPeriod =  $INFOFACTWATERING[1]['TotalPeriod'] - $min;
                $sql = "UPDATE `fact-watering` SET `Modify` = '$time', `RainPeriod` = '$RainPeriod', `TotalPeriod` = '$TotalPeriod' WHERE `fact-watering`.`ID` = {$INFOFACTWATERING[1]['ID']}";
                updateData($sql);
            } else {
                $WaterPeriod =  $INFOFACTWATERING[1]['WaterPeriod'] - $min;
                $TotalPeriod =  $INFOFACTWATERING[1]['TotalPeriod'] - $min;
                $sql = "UPDATE `fact-watering` SET `Modify` = '$time', `WaterPeriod` = '$WaterPeriod', `TotalPeriod` = '$TotalPeriod' WHERE `fact-watering`.`ID` = {$INFOFACTWATERING[1]['ID']}";
                updateData($sql);
            }
        } else {
            $sql = "DELETE FROM `fact-watering` WHERE `fact-watering`.`ID` = {$INFOFACTWATERING[1]['ID']}";
            deletedata($sql);
            $datetomorrow = date('Y-m-d', strtotime('+1 day', strtotime($date)));
            $dateyesterday = date('Y-m-d', strtotime('-1 day', strtotime($date)));
            $DIMDATEYESTERDAY = getDIMDate($dateyesterday);
            $DIMDATETOMORROW = getDIMDate($datetomorrow);
            $DIMDATE = getDIMDate($date);


            $sql = "SELECT `fact-drying`.*, STARTDATE.`Date` AS StartTime, ENDDATE.`Date` AS EndTime FROM  `fact-drying` 
                INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `fact-drying`.`DIMsubFID`
                INNER JOIN  `dim-time` AS STARTDATE  ON   STARTDATE.`ID` = `fact-drying`.`DIMstartDID`
                LEFT JOIN  `dim-time` AS ENDDATE  ON   ENDDATE.`ID` = `fact-drying`.`DIMstopDID`
                WHERE`dim-farm`.`dbID` = $FSID
                ORDER BY  STARTDATE.`Date`";
            $MINFACTDRAING = selectData($sql);
            if ($MINFACTDRAING[1]['StartTime'] == $datetomorrow) {
                $sql = "DELETE FROM `fact-drying` WHERE  `fact-drying`.`ID` = {$MINFACTDRAING[1]['ID']}";
                deletedata($sql);
            } else {
                $sql = "SELECT * FROM `fact-watering` 
                        INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `fact-watering`.`DIMsubFID`
                        INNER JOIN `dim-time` ON `dim-time`.`ID` = `fact-watering`.`DIMdateID`
                        WHERE `dim-time`.`Date` = '$datetomorrow'  AND `dim-farm`.`dbID` = $FSID";
                $CHECKTOMORROW = selectData($sql);
                $sql = "SELECT * FROM `fact-watering` 
                        INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `fact-watering`.`DIMsubFID`
                        INNER JOIN `dim-time` ON `dim-time`.`ID` = `fact-watering`.`DIMdateID`
                        WHERE `dim-time`.`Date` = '$dateyesterday'  AND `dim-farm`.`dbID` = $FSID";
                $CHECKYESYERDAT = selectData($sql);
                if ($CHECKTOMORROW[0]['numrow'] == 1 && $CHECKYESYERDAT[0]['numrow'] == 1) {
                    $sql = "INSERT INTO `fact-drying` (`ID`, `Modify`, `LOGloginID`, `DIMstartDID`, `DIMstopDID`, `DIMownerID`, `DIMfarmID`, `DIMsubFID`, `Period`) 
                            VALUES (NULL, '$time', '{$LOG_LOGIN[1]['ID']}', '{$DIMDATE[1]['ID']}', '{$DIMDATETOMORROW[1]['ID']}', '{$CHECKTOMORROW[1]['DIMownerID']}', '{$CHECKTOMORROW[1]['DIMfarmID']}', '{$CHECKTOMORROW[1]['DIMsubFID']}', '1')";
                    addinsertData($sql);
                } else if ($CHECKTOMORROW[0]['numrow'] == 1 && $CHECKYESYERDAT[0]['numrow'] == 0) {
                    $sql = "SELECT `fact-drying`.*, STARTDATE.`Date` AS StartTime, ENDDATE.`Date` AS EndTime FROM  `fact-drying` 
                            INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `fact-drying`.`DIMsubFID`
                            INNER JOIN  `dim-time` AS STARTDATE  ON   STARTDATE.`ID` = `fact-drying`.`DIMstartDID`
                            INNER JOIN  `dim-time` AS ENDDATE  ON   ENDDATE.`ID` = `fact-drying`.`DIMstopDID`
                            WHERE ENDDATE.`Date` = '$date'  AND `dim-farm`.`dbID` = $FSID";
                    $INFOFACT = selectData($sql);
                    $p = $INFOFACT[1]['Period'] + 1;
                    $sql = "UPDATE `fact-drying` SET `Modify` = '$time',`DIMstopDID` = '{$DIMDATETOMORROW[1]['ID']}', `Period` = '$p' WHERE `fact-drying`.`ID` = {$INFOFACT[1]['ID']}";
                    updateData($sql);
                } else if ($CHECKTOMORROW[0]['numrow'] == 0 && $CHECKYESYERDAT[0]['numrow'] == 1) {
                    $sql = "SELECT `fact-drying`.*, STARTDATE.`Date` AS StartTime, ENDDATE.`Date` AS EndTime FROM  `fact-drying` 
                            INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `fact-drying`.`DIMsubFID`
                            INNER JOIN  `dim-time` AS STARTDATE  ON   STARTDATE.`ID` = `fact-drying`.`DIMstartDID`
                            INNER JOIN  `dim-time` AS ENDDATE  ON   ENDDATE.`ID` = `fact-drying`.`DIMstopDID`
                            WHERE STARTDATE.`Date` = '$datetomorrow'  AND `dim-farm`.`dbID` = $FSID";
                    $INFOFACT = selectData($sql);
                    $p = $INFOFACT[1]['Period'] + 1;
                    $sql = "UPDATE `fact-drying` SET `Modify` = '$time',`DIMstartDID` = '{$DIMDATE[1]['ID']}', `Period` = '$p' WHERE `fact-drying`.`ID` = {$INFOFACT[1]['ID']}";
                    updateData($sql);
                } else {
                    $sql = "SELECT `fact-drying`.*, STARTDATE.`Date` AS StartTime, ENDDATE.`Date` AS EndTime FROM  `fact-drying` 
                    INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `fact-drying`.`DIMsubFID`
                    INNER JOIN  `dim-time` AS STARTDATE  ON   STARTDATE.`ID` = `fact-drying`.`DIMstartDID`
                    INNER JOIN  `dim-time` AS ENDDATE  ON   ENDDATE.`ID` = `fact-drying`.`DIMstopDID`
                    WHERE ENDDATE.`Date` = '$date'  AND `dim-farm`.`dbID` = $FSID";
                    $INFOONE = selectData($sql);
                    $sql = "SELECT `fact-drying`.*, STARTDATE.`Date` AS StartTime, IFNULL(ENDDATE.`Date`,'0') AS EndTime  FROM  `fact-drying` 
                    INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `fact-drying`.`DIMsubFID`
                    INNER JOIN  `dim-time` AS STARTDATE  ON   STARTDATE.`ID` = `fact-drying`.`DIMstartDID`
                    LEFT JOIN  `dim-time` AS ENDDATE  ON   ENDDATE.`ID` = `fact-drying`.`DIMstopDID`
                    WHERE STARTDATE.`Date` = '$datetomorrow'  AND `dim-farm`.`dbID` = $FSID";
                    $INFOTWO = selectData($sql);
                    if ($INFOTWO[1]['EndTime'] != '0') {
                        $p1 = date_diff(date_create($INFOONE[1]['StartTime']), date_create($INFOTWO[1]['EndTime']))->format("%a");
                        $sql = "INSERT INTO `fact-drying` (`ID`, `Modify`, `LOGloginID`, `DIMstartDID`, `DIMstopDID`, `DIMownerID`, `DIMfarmID`, `DIMsubFID`, `Period`) 
                            VALUES (NULL, '$time', '{$LOG_LOGIN[1]['ID']}', '{$INFOONE[1]['DIMstartDID']}', '{$INFOTWO[1]['DIMstopDID']}', '{$INFOTWO[1]['DIMownerID']}', '{$INFOTWO[1]['DIMfarmID']}', '{$INFOTWO[1]['DIMsubFID']}', '$p1')";
                        addinsertData($sql);
                        $sql = "DELETE FROM `fact-drying` WHERE `fact-drying`.`ID` = {$INFOONE[1]['ID']}  OR  `fact-drying`.`ID` = {$INFOTWO[1]['ID']}";
                        deletedata($sql);
                    } else {
                        $sql = "INSERT INTO `fact-drying` (`ID`, `Modify`, `LOGloginID`, `DIMstartDID`, `DIMstopDID`, `DIMownerID`, `DIMfarmID`, `DIMsubFID`, `Period`) 
                            VALUES (NULL, '$time', '{$LOG_LOGIN[1]['ID']}', '{$INFOONE[1]['DIMstartDID']}', NULL, '{$INFOTWO[1]['DIMownerID']}', '{$INFOTWO[1]['DIMfarmID']}', '{$INFOTWO[1]['DIMsubFID']}', '0')";
                        addinsertData($sql);
                        $sql = "DELETE FROM `fact-drying` WHERE `fact-drying`.`ID` = {$INFOONE[1]['ID']}  OR  `fact-drying`.`ID` = {$INFOTWO[1]['ID']}";
                        deletedata($sql);
                    }
                }
            }
        }

        /////////////////////////////////////
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
        $TypeDetail = $_POST['TypeDetail'] ?? "";
        $dateRain = $_POST['dateRain'];
        $dimFarmIDRian = $_POST['FarmIDRian'];
        $dimSubFarmIDRian = $_POST['SubFarmIDRian'];
        $sql = "SELECT * FROM `dim-farm` WHERE `dim-farm`.`ID` = $dimSubFarmIDRian";
        $DATA = selectData($sql);
        $FSID = $DATA[1]['dbID'];
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
        //////////////////////// update fact-watering fact-Drying
        $sql = "SELECT * ,`fact-watering`.`ID` AS factWaterID FROM `fact-watering` 
         INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `fact-watering`.`DIMsubFID`
         WHERE `dim-farm`.`dbID` = $FSID AND `fact-watering`.`DIMdateID` = {$DIMDATE[1]['ID']}";
        echo $sql;
        $FACTWATERING = selectData($sql);
        if ($FACTWATERING[0]['numrow'] != 0) {
            echo "0";
            $RainPeriod = $FACTWATERING[1]['RainPeriod'] + $min;
            $TotalPeriod = $FACTWATERING[1]['TotalPeriod'] + $min;
            $sql = "UPDATE `fact-watering` SET `Modify` = '$time', `RainPeriod` = '$RainPeriod',
              `TotalPeriod` = '$TotalPeriod' WHERE `fact-watering`.`ID` = {$FACTWATERING[1]['factWaterID']}";
            updateData($sql);
        } else {
            echo "1";
            $sql = "INSERT INTO `fact-watering` (`ID`, `Modify`, `LOGloginID`, `DIMdateID`, `DIMownerID`, `DIMfarmID`, `DIMsubFID`, `WaterPeriod`, `RainPeriod`, `TotalPeriod`) VALUES 
             (NULL, '$time', '{$LOG_LOGIN[1]['ID']}', '{$DIMDATE[1]['ID']}', '$dimOwnerID', '$dimFarmIDRian', '$dimSubFarmIDRian', '0', '$min', '$min')";
            addinsertData($sql);
            $sql = "SELECT `fact-drying`.*,`dim-time`.`Date` AS StartTime FROM `fact-drying` 
             INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `fact-drying`.`DIMsubFID`
             INNER JOIN `dim-time` ON `dim-time`.`ID` = `fact-drying`.`DIMstartDID`
             WHERE `dim-farm`.`dbID` =$FSID AND `fact-drying`.`DIMstopDID` IS NULL ";
            $CHECKDrying = selectData($sql);
            $datetomorrow = date('Y-m-d', strtotime('+1 day', strtotime($dateRain)));
            $dateyesterday = date('Y-m-d', strtotime('-1 day', strtotime($dateRain)));
            if ($CHECKDrying[0]['numrow'] != 0) {
                echo "0";
                $sql = "SELECT `fact-drying`.*, STARTDATE.`Date` AS StartTime, ENDDATE.`Date` AS EndTime FROM `fact-drying` 
                 INNER JOIN  `dim-time` AS STARTDATE  ON   STARTDATE.`ID` = `fact-drying`.`DIMstartDID`
                 INNER JOIN  `dim-time` AS ENDDATE  ON   ENDDATE.`ID` = `fact-drying`.`DIMstopDID`
                  INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `fact-drying`.`DIMsubFID`
                  WHERE `dim-farm`.`dbID` = $FSID AND  STARTDATE.`Date` <= '$dateRain' AND  ENDDATE.`Date` >= '$dateRain'";
                $FACTDRYING = selectData($sql);

                if ($FACTDRYING[0]['numrow'] != 0) {
                    echo "0";
                    if ($FACTDRYING[1]['StartTime'] == $dateRain && $FACTDRYING[1]['Period'] == 1) {
                        echo "01";
                        $sql = "DELETE FROM `fact-drying` WHERE `fact-drying`.`ID` = {$FACTDRYING[1]['ID']}";
                        deletedata($sql);
                    } else if ($FACTDRYING[1]['StartTime'] == $dateRain) {
                        echo "02";
                        $DIMDATETOMORROW = getDIMDate($datetomorrow);
                        $diffPeriod =  $FACTDRYING[1]['Period'] - 1;
                        $sql = "UPDATE `fact-drying` SET `DIMstartDID` = '{$DIMDATETOMORROW[1]['ID']}',`Modify` = '$time', `Period` = '$diffPeriod'
                          WHERE `fact-drying`.`ID` = {$FACTDRYING[1]['ID']}";
                        updateData($sql);
                    } else if ($FACTDRYING[1]['EndTime'] == $datetomorrow) {
                        echo "03";
                        $DIMDATEYESTERDAY = getDIMDate($dateyesterday);
                        $diffPeriod =  $FACTDRYING[1]['Period'] - 1;
                        $sql = "UPDATE `fact-drying` SET `DIMstopDID` = '{$DIMDATE[1]['ID']}',`Modify` = '$time', `Period` = '$diffPeriod'
                          WHERE `fact-drying`.`ID` = {$FACTDRYING[1]['ID']}";
                        echo $sql;
                        updateData($sql);
                    } else if ($FACTDRYING[1]['EndTime'] == $dateRain) {
                        echo "04";
                    } else {
                        echo "05";
                        $DIMDATETOMORROW = getDIMDate($datetomorrow);
                        $p1 = date_diff(date_create($FACTDRYING[1]['StartTime']), date_create($dateRain))->format("%a");
                        $sql = "INSERT INTO `fact-drying` (`ID`, `Modify`, `LOGloginID`, `DIMstartDID`, `DIMstopDID`, `DIMownerID`, `DIMfarmID`, `DIMsubFID`, `Period`) 
                         VALUES (NULL, '$time', '{$LOG_LOGIN[1]['ID']}', '{$FACTDRYING[1]['DIMstartDID']}', '{$DIMDATE[1]['ID']}', '{$FACTDRYING[1]['DIMownerID']}', '{$FACTDRYING[1]['DIMfarmID']}', '{$FACTDRYING[1]['DIMsubFID']}', '$p1')";
                        addinsertData($sql);

                        $p2 =  date_diff(date_create($datetomorrow), date_create($FACTDRYING[1]['EndTime']))->format("%a");
                        $sql = "INSERT INTO `fact-drying` (`ID`, `Modify`, `LOGloginID`, `DIMstartDID`, `DIMstopDID`, `DIMownerID`, `DIMfarmID`, `DIMsubFID`, `Period`) 
                         VALUES (NULL, '$time', '{$LOG_LOGIN[1]['ID']}', '{$DIMDATETOMORROW[1]['ID']}', '{$FACTDRYING[1]['DIMstopDID']}', '{$FACTDRYING[1]['DIMownerID']}', '{$FACTDRYING[1]['DIMfarmID']}', '{$FACTDRYING[1]['DIMsubFID']}', '$p2')";
                        addinsertData($sql);
                        $sql = "DELETE FROM `fact-drying` WHERE `fact-drying`.`ID` = {$FACTDRYING[1]['ID']}";
                        deletedata($sql);
                    }
                } else {
                    echo "1";
                    if ($CHECKDrying[1]['StartTime'] <= $dateRain) {
                        echo "0";
                        $DIMDATETOMORROW = getDIMDate($datetomorrow);
                        if ($CHECKDrying[1]['StartTime'] == $dateRain) {
                            echo "0";
                            $sql = "UPDATE `fact-drying` SET `DIMstartDID` = '{$DIMDATETOMORROW[1]['ID']}',`Modify` = '$time'  WHERE `fact-drying`.`ID` = {$CHECKDrying[1]['ID']}";
                            updateData($sql);
                        } else {
                            echo "1";
                            $p1 = date_diff(date_create($CHECKDrying[1]['StartTime']), date_create($dateRain))->format("%a");
                            $sql = "INSERT INTO `fact-drying` (`ID`, `Modify`, `LOGloginID`, `DIMstartDID`, `DIMstopDID`, `DIMownerID`, `DIMfarmID`, `DIMsubFID`, `Period`) 
                                    VALUES (NULL, '$time', '{$LOG_LOGIN[1]['ID']}', '{$CHECKDrying[1]['DIMstartDID']}', '{$DIMDATE[1]['ID']}', '{$CHECKDrying[1]['DIMownerID']}', '{$CHECKDrying[1]['DIMfarmID']}', '{$CHECKDrying[1]['DIMsubFID']}', '$p1')";
                            addinsertData($sql);
                            $sql = "INSERT INTO `fact-drying` (`ID`, `Modify`, `LOGloginID`, `DIMstartDID`, `DIMstopDID`, `DIMownerID`, `DIMfarmID`, `DIMsubFID`, `Period`) 
                                    VALUES (NULL, '$time', '{$LOG_LOGIN[1]['ID']}', '{$DIMDATETOMORROW[1]['ID']}', NULL , '$dimOwnerID', '$dimFarmIDRian', '$dimSubFarmIDRian', '0')";
                            addinsertData($sql);
                            $sql = "DELETE FROM `fact-drying` WHERE `fact-drying`.`ID` = {$CHECKDrying[1]['ID']}";
                            deletedata($sql);
                            echo "p1" . $p1;
                        }
                    } else {
                        echo "1";
                        $sql = "SELECT `fact-drying`.*, STARTDATE.`Date` AS StartTime, ENDDATE.`Date` AS EndTime FROM `fact-drying` 
                         INNER JOIN  `dim-time` AS STARTDATE  ON   STARTDATE.`ID` = `fact-drying`.`DIMstartDID`
                         INNER JOIN  `dim-time` AS ENDDATE  ON   ENDDATE.`ID` = `fact-drying`.`DIMstopDID`
                          INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `fact-drying`.`DIMsubFID`
                          WHERE `dim-farm`.`dbID` =$FSID AND  ENDDATE.`Date`IS NOT NULL
                          ORDER BY STARTDATE.`Date` LIMIT 1";
                        $MINFACTDRAING = selectData($sql);
                        if ($MINFACTDRAING[0]['numrow'] > 0) {
                            echo "0";
                            $DIMDATETOMORROW = getDIMDate($datetomorrow);
                            $p1 =   date_diff(date_create($DIMDATETOMORROW[1]['Date']), date_create($MINFACTDRAING[1]['EndTime']))->format("%a");
                            $sql = "UPDATE `fact-drying` SET `Modify` = '$time',`DIMstartDID` = '{$DIMDATETOMORROW[1]['ID']}', `Period` = '$p1' WHERE `fact-drying`.`ID` = {$MINFACTDRAING[1]['ID']}";
                            updateData($sql);
                        } else {
                            echo "1";
                            $DIMDATETOMORROW = getDIMDate($datetomorrow);
                            $sql = "SELECT `fact-drying`.*, STARTDATE.`Date` AS StartTime, ENDDATE.`Date` AS EndTime FROM `fact-drying` 
                             INNER JOIN  `dim-time` AS STARTDATE  ON   STARTDATE.`ID` = `fact-drying`.`DIMstartDID`
                             LEFT JOIN  `dim-time` AS ENDDATE  ON   ENDDATE.`ID` = `fact-drying`.`DIMstopDID`
                             INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `fact-drying`.`DIMsubFID`
                             WHERE `dim-farm`.`dbID` =$FSID AND  ENDDATE.`Date`IS  NULL
                             ORDER BY STARTDATE.`Date` LIMIT 1";
                            $MINFACTDRAING = selectData($sql);
                            $sql = "UPDATE `fact-drying` SET `Modify` = '$time',`DIMstartDID` = '{$DIMDATETOMORROW[1]['ID']}' WHERE `fact-drying`.`ID` = {$MINFACTDRAING[1]['ID']}";
                            updateData($sql);
                        }
                    }
                }
            } else {
                echo "1";
                $DIMDATETOMORROW = getDIMDate($datetomorrow);
                $sql = "INSERT INTO `fact-drying` (`ID`, `Modify`, `LOGloginID`, `DIMstartDID`, `DIMstopDID`, `DIMownerID`, `DIMfarmID`, `DIMsubFID`, `Period`) 
                         VALUES (NULL, '$time', '{$LOG_LOGIN[1]['ID']}', '{$DIMDATETOMORROW[1]['ID']}', NULL, '$dimOwnerID', '$dimFarmIDRian', '$dimSubFarmIDRian', '0')";
                addinsertData($sql);
            }
        }
        ////////////////////////////////
        if ($TypeDetail == "Detail") {
            header("location:WaterDetail.php?FSID=$FSID&Active=3");
        } else {
            header("location:Water.php");
        }
        break;
    case 'AddWater';

        $TypeDetail = $_POST['TypeDetail'] ?? "";
        $dateWater = $_POST['dateWater'];
        $dimFarmIDWater = $_POST['FarmIDWater'];
        $dimSubFarmIDWater = $_POST['SubFarmIDWater'];
        $timeStratWater = $_POST['timeStratWater'];
        $timeEndWater = $_POST['timeEndWater'];
        $sql = "SELECT * FROM `dim-farm` WHERE `dim-farm`.`ID` = $dimSubFarmIDWater";
        $DATA = selectData($sql);
        $FSID = $DATA[1]['dbID'];
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
        //////////////////////// update fact-watering fact-Drying
        $sql = "SELECT * ,`fact-watering`.`ID` AS factWaterID FROM `fact-watering` 
        INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `fact-watering`.`DIMsubFID`
        WHERE `dim-farm`.`dbID` = $FSID AND `fact-watering`.`DIMdateID` = {$DIMDATE[1]['ID']}";
        $FACTWATERING = selectData($sql);
        if ($FACTWATERING[0]['numrow'] != 0) {
            echo "0";
            $WaterPeriod = $FACTWATERING[1]['WaterPeriod'] + $min;
            $TotalPeriod = $FACTWATERING[1]['TotalPeriod'] + $min;
            $sql = "UPDATE `fact-watering` SET `Modify` = '$time', `WaterPeriod` = '$WaterPeriod',
             `TotalPeriod` = '$TotalPeriod' WHERE `fact-watering`.`ID` = {$FACTWATERING[1]['factWaterID']}";
            updateData($sql);
        } else {
            echo "1";
            $sql = "INSERT INTO `fact-watering` (`ID`, `Modify`, `LOGloginID`, `DIMdateID`, `DIMownerID`, `DIMfarmID`, `DIMsubFID`, `WaterPeriod`, `RainPeriod`, `TotalPeriod`) VALUES 
            (NULL, '$time', '{$LOG_LOGIN[1]['ID']}', '{$DIMDATE[1]['ID']}', '$dimOwnerID', '$dimFarmIDWater', '$dimSubFarmIDWater', '$min', '0', '$min')";
            addinsertData($sql);
            $sql = "SELECT `fact-drying`.*,`dim-time`.`Date` AS StartTime FROM `fact-drying` 
            INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `fact-drying`.`DIMsubFID`
            INNER JOIN `dim-time` ON `dim-time`.`ID` = `fact-drying`.`DIMstartDID`
            WHERE `dim-farm`.`dbID` =$FSID AND `fact-drying`.`DIMstopDID` IS NULL ";
            $CHECKDrying = selectData($sql);
            $datetomorrow = date('Y-m-d', strtotime('+1 day', strtotime($dateWater)));
            $dateyesterday = date('Y-m-d', strtotime('-1 day', strtotime($dateWater)));
            if ($CHECKDrying[0]['numrow'] != 0) {
                echo "0";
                $sql = "SELECT `fact-drying`.*, STARTDATE.`Date` AS StartTime, ENDDATE.`Date` AS EndTime FROM `fact-drying` 
                INNER JOIN  `dim-time` AS STARTDATE  ON   STARTDATE.`ID` = `fact-drying`.`DIMstartDID`
                INNER JOIN  `dim-time` AS ENDDATE  ON   ENDDATE.`ID` = `fact-drying`.`DIMstopDID`
                 INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `fact-drying`.`DIMsubFID`
                 WHERE `dim-farm`.`dbID` = $FSID AND  STARTDATE.`Date` <= '$dateWater' AND  ENDDATE.`Date` >= '$dateWater'";
                $FACTDRYING = selectData($sql);

                if ($FACTDRYING[0]['numrow'] != 0) {
                    echo "0";
                    if ($FACTDRYING[1]['StartTime'] == $dateWater && $FACTDRYING[1]['Period'] == 1) {
                        echo "01";
                        $sql = "DELETE FROM `fact-drying` WHERE `fact-drying`.`ID` = {$FACTDRYING[1]['ID']}";
                        deletedata($sql);
                    } else if ($FACTDRYING[1]['StartTime'] == $dateWater) {
                        echo "02";
                        $DIMDATETOMORROW = getDIMDate($datetomorrow);
                        $diffPeriod =  $FACTDRYING[1]['Period'] - 1;
                        $sql = "UPDATE `fact-drying` SET `DIMstartDID` = '{$DIMDATETOMORROW[1]['ID']}',`Modify` = '$time', `Period` = '$diffPeriod'
                         WHERE `fact-drying`.`ID` = {$FACTDRYING[1]['ID']}";
                        updateData($sql);
                    } else if ($FACTDRYING[1]['EndTime'] == $datetomorrow) {
                        echo "03";
                        $DIMDATEYESTERDAY = getDIMDate($dateyesterday);
                        $diffPeriod =  $FACTDRYING[1]['Period'] - 1;
                        $sql = "UPDATE `fact-drying` SET `DIMstopDID` = '{$DIMDATE[1]['ID']}',`Modify` = '$time', `Period` = '$diffPeriod'
                         WHERE `fact-drying`.`ID` = {$FACTDRYING[1]['ID']}";
                        echo $sql;
                        updateData($sql);
                    } else if ($FACTDRYING[1]['EndTime'] == $dateWater) {
                        echo "04";
                    } else {
                        echo "05";
                        $DIMDATETOMORROW = getDIMDate($datetomorrow);
                        $p1 = date_diff(date_create($FACTDRYING[1]['StartTime']), date_create($dateWater))->format("%a");
                        $sql = "INSERT INTO `fact-drying` (`ID`, `Modify`, `LOGloginID`, `DIMstartDID`, `DIMstopDID`, `DIMownerID`, `DIMfarmID`, `DIMsubFID`, `Period`) 
                        VALUES (NULL, '$time', '{$LOG_LOGIN[1]['ID']}', '{$FACTDRYING[1]['DIMstartDID']}', '{$DIMDATE[1]['ID']}', '{$FACTDRYING[1]['DIMownerID']}', '{$FACTDRYING[1]['DIMfarmID']}', '{$FACTDRYING[1]['DIMsubFID']}', '$p1')";
                        addinsertData($sql);

                        $p2 =  date_diff(date_create($datetomorrow), date_create($FACTDRYING[1]['EndTime']))->format("%a");
                        $sql = "INSERT INTO `fact-drying` (`ID`, `Modify`, `LOGloginID`, `DIMstartDID`, `DIMstopDID`, `DIMownerID`, `DIMfarmID`, `DIMsubFID`, `Period`) 
                        VALUES (NULL, '$time', '{$LOG_LOGIN[1]['ID']}', '{$DIMDATETOMORROW[1]['ID']}', '{$FACTDRYING[1]['DIMstopDID']}', '{$FACTDRYING[1]['DIMownerID']}', '{$FACTDRYING[1]['DIMfarmID']}', '{$FACTDRYING[1]['DIMsubFID']}', '$p2')";
                        addinsertData($sql);
                        $sql = "DELETE FROM `fact-drying` WHERE `fact-drying`.`ID` = {$FACTDRYING[1]['ID']}";
                        deletedata($sql);
                    }
                } else {
                    echo "1";
                    if ($CHECKDrying[1]['StartTime'] <= $dateWater) {
                        echo "0";
                        $DIMDATETOMORROW = getDIMDate($datetomorrow);

                        if ($CHECKDrying[1]['StartTime'] == $dateWater) {
                            echo "0";
                            $sql = "UPDATE `fact-drying` SET `DIMstartDID` = '{$DIMDATETOMORROW[1]['ID']}',`Modify` = '$time'  WHERE `fact-drying`.`ID` = {$CHECKDrying[1]['ID']}";
                            updateData($sql);
                        } else {
                            echo "1";
                            $p1 = date_diff(date_create($CHECKDrying[1]['StartTime']), date_create($dateWater))->format("%a");
                            $sql = "INSERT INTO `fact-drying` (`ID`, `Modify`, `LOGloginID`, `DIMstartDID`, `DIMstopDID`, `DIMownerID`, `DIMfarmID`, `DIMsubFID`, `Period`) 
                                    VALUES (NULL, '$time', '{$LOG_LOGIN[1]['ID']}', '{$CHECKDrying[1]['DIMstartDID']}', '{$DIMDATE[1]['ID']}', '{$CHECKDrying[1]['DIMownerID']}', '{$CHECKDrying[1]['DIMfarmID']}', '{$CHECKDrying[1]['DIMsubFID']}', '$p1')";
                            addinsertData($sql);
                            $sql = "INSERT INTO `fact-drying` (`ID`, `Modify`, `LOGloginID`, `DIMstartDID`, `DIMstopDID`, `DIMownerID`, `DIMfarmID`, `DIMsubFID`, `Period`) 
                                    VALUES (NULL, '$time', '{$LOG_LOGIN[1]['ID']}', '{$DIMDATETOMORROW[1]['ID']}', NULL , '$dimOwnerID', '$dimFarmIDWater', '$dimSubFarmIDWater', '0')";
                            addinsertData($sql);
                            $sql = "DELETE FROM `fact-drying` WHERE `fact-drying`.`ID` = {$CHECKDrying[1]['ID']}";
                            deletedata($sql);
                            echo "p1" . $p1;
                        }
                    } else {
                        echo "1";
                        $sql = "SELECT `fact-drying`.*, STARTDATE.`Date` AS StartTime, ENDDATE.`Date` AS EndTime FROM `fact-drying` 
                        INNER JOIN  `dim-time` AS STARTDATE  ON   STARTDATE.`ID` = `fact-drying`.`DIMstartDID`
                        INNER JOIN  `dim-time` AS ENDDATE  ON   ENDDATE.`ID` = `fact-drying`.`DIMstopDID`
                         INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `fact-drying`.`DIMsubFID`
                         WHERE `dim-farm`.`dbID` =$FSID AND  ENDDATE.`Date`IS NOT NULL
                         ORDER BY STARTDATE.`Date` LIMIT 1";
                        $MINFACTDRAING = selectData($sql);
                        if ($MINFACTDRAING[0]['numrow'] > 0) {
                            echo "0";
                            $DIMDATETOMORROW = getDIMDate($datetomorrow);
                            $p1 =   date_diff(date_create($DIMDATETOMORROW[1]['Date']), date_create($MINFACTDRAING[1]['EndTime']))->format("%a");
                            $sql = "UPDATE `fact-drying` SET `Modify` = '$time',`DIMstartDID` = '{$DIMDATETOMORROW[1]['ID']}', `Period` = '$p1' WHERE `fact-drying`.`ID` = {$MINFACTDRAING[1]['ID']}";
                            updateData($sql);
                        } else {
                            echo "1";
                            $DIMDATETOMORROW = getDIMDate($datetomorrow);
                            $sql = "SELECT `fact-drying`.*, STARTDATE.`Date` AS StartTime, ENDDATE.`Date` AS EndTime FROM `fact-drying` 
                            INNER JOIN  `dim-time` AS STARTDATE  ON   STARTDATE.`ID` = `fact-drying`.`DIMstartDID`
                            LEFT JOIN  `dim-time` AS ENDDATE  ON   ENDDATE.`ID` = `fact-drying`.`DIMstopDID`
                            INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `fact-drying`.`DIMsubFID`
                            WHERE `dim-farm`.`dbID` =$FSID AND  ENDDATE.`Date`IS  NULL
                            ORDER BY STARTDATE.`Date` LIMIT 1";
                            $MINFACTDRAING = selectData($sql);
                            $sql = "UPDATE `fact-drying` SET `Modify` = '$time',`DIMstartDID` = '{$DIMDATETOMORROW[1]['ID']}' WHERE `fact-drying`.`ID` = {$MINFACTDRAING[1]['ID']}";
                            updateData($sql);
                        }
                    }
                }
            } else {
                echo "1";
                $DIMDATETOMORROW = getDIMDate($datetomorrow);
                $sql = "INSERT INTO `fact-drying` (`ID`, `Modify`, `LOGloginID`, `DIMstartDID`, `DIMstopDID`, `DIMownerID`, `DIMfarmID`, `DIMsubFID`, `Period`) 
                        VALUES (NULL, '$time', '{$LOG_LOGIN[1]['ID']}', '{$DIMDATETOMORROW[1]['ID']}', NULL, '$dimOwnerID', '$dimFarmIDWater', '$dimSubFarmIDWater', '0')";
                addinsertData($sql);
            }
        }
        //////////////////////
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
