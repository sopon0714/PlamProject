<?php

require_once("../../dbConnect.php");
$myConDB = connectDB();
date_default_timezone_set("Asia/Bangkok");

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
if ($result == 'getTextInFo') {
    $fpro = $_POST["fpro"] ?? '';
    $fdist = $_POST["fdist"] ?? '';
    $fullname = $_POST["fullname"] ?? '';
    $status = $_POST["status"] ?? '';
    $date = $_POST["date"] ?? '';
    $fullname = rtrim($fullname);
    $fullname = preg_replace('/[[:space:]]+/', ' ', trim($fullname));
    $namef = explode(" ", $fullname);
    $text = "";
    if (isset($namef[1])) {
        $fnamef = $namef[0];
        $lnamef = $namef[1];
    } else {
        $fnamef = $fullname;
        $lnamef = $fullname;
    }

    $sql = "SELECT SUBFARM.`dbID` AS FSID,`dim-user`.`FullName`,FARM.`Alias` as NameFarm, SUBFARM.`Alias` as NamesubFarm
            FROM `log-farm`
            INNER JOIN `dim-farm` AS SUBFARM ON SUBFARM.`ID` =`log-farm`.`DIMSubfID` 
            INNER JOIN `dim-farm` AS FARM ON FARM.`ID` =`log-farm`.`DIMfarmID` 
            INNER JOIN `dim-user` ON `dim-user`.`ID` = `log-farm`.`DIMownerID`
            INNER JOIN `dim-address` ON `dim-address`.`ID` = `log-farm`.`DIMaddrID`
            WHERE  `log-farm`.`ID` IN
            (SELECT MAX(`log-farm`.`ID`)  as ID FROM `log-farm` INNER JOIN `dim-farm` ON `dim-farm`.`ID` =`log-farm`.`DIMSubfID`
            GROUP BY `dim-farm`.`dbID` ) ";
    if ($fullname != '') $sql .= " AND (FullName LIKE '%" . $fnamef . "%' OR FullName LIKE '%" . $lnamef . "%') ";
    if ($fpro    != 0)  $sql .= " AND `dim-address`.dbprovID = '" . $fpro . "' ";
    if ($fdist   != 0)  $sql .= " AND `dim-address`.dbDistID = '" . $fdist . "' ";
    $sql .= " ORDER BY `dim-user`.`FullName`";
    $DATASUBFARM = selectData($sql);
    if ($DATASUBFARM[0]['numrow'] != 0) {
        $INFOFARM = [];
        $text1 = "(";
        for ($i = 1; $i <= $DATASUBFARM[0]['numrow']; $i++) {
            $text1 .= "'{$DATASUBFARM[$i]['FSID']}',";
            $INFOFARM[$DATASUBFARM[$i]['FSID']]['NameFarm'] = $DATASUBFARM[$i]['NameFarm'];
            $INFOFARM[$DATASUBFARM[$i]['FSID']]['NamesubFarm'] = $DATASUBFARM[$i]['NamesubFarm'];
        }
        $text1 = substr($text1, 0, -1) . ")";
        if ($status == 'เก็บเกี่ยว') {
            $sql = "SELECT `dim-farm`.`dbID` AS FSID , `log-harvest`.`Weight`  FROM `log-harvest`
            INNER JOIN `dim-time` ON `dim-time`.`ID` = `log-harvest`.`DIMdateID`
            INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `log-harvest`.`DIMsubFID`
            WHERE  `log-harvest`.`isDelete` = 0 AND `dim-farm`.`dbID` IN $text1 AND `dim-time`.`Date` = '$date'
            ORDER BY   `dim-farm`.`dbID` ";
            $DATA = selectData($sql);

            $len = $DATA[0]['numrow'];
            for ($i = 1; $i <= $len; $i++) {
                $text .= "<tr>
                            <td class=\"text-left\">{$INFOFARM[$DATA[$i]['FSID']]['NameFarm']}</td>
                            <td class=\"text-left\">{$INFOFARM[$DATA[$i]['FSID']]['NamesubFarm']}</td>
                            <td class=\"text-left\">เก็บเกี่ยวผลผลิตได้ {$DATA[$i]['Weight']} กก.</td>
                        </tr>";
            }
        } else  if ($status == 'ฝนตก') {
            $sql = "SELECT `dim-farm`.`dbID` AS FSID , `log-raining`.`Vol` , `log-raining`.`StartTime` , `log-raining`.`StopTime` FROM `log-raining`
            INNER JOIN `dim-time` ON `dim-time`.`ID` = `log-raining`.`DIMdateID`
            INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `log-raining`.`DIMsubFID`
            WHERE  `log-raining`.`isDelete` = 0 AND `dim-farm`.`dbID` IN $text1 AND `dim-time`.`Date` = '$date'
            ORDER BY   `dim-farm`.`dbID` ";
            $DATA = selectData($sql);

            $len = $DATA[0]['numrow'];
            for ($i = 1; $i <= $len; $i++) {
                $text .= "<tr>
                            <td class=\"text-left\">{$INFOFARM[$DATA[$i]['FSID']]['NameFarm']}</td>
                            <td class=\"text-left\">{$INFOFARM[$DATA[$i]['FSID']]['NamesubFarm']}</td>
                            <td class=\"text-left\">ฝนตกช่วง " . date("H.i", $DATA[$i]['StartTime']) . " - " . date("H.i", $DATA[$i]['StopTime']) . " น. ปริมาตร {$DATA[$i]['Vol']} มม.</td>
                        </tr>";
            }
        } else  if ($status == 'รดน้ำ') {
            $sql = "SELECT `dim-farm`.`dbID` AS FSID , `log-watering`.`Vol` , `log-watering`.`StartTime` , `log-watering`.`StopTime` FROM `log-watering`
            INNER JOIN `dim-time` ON `dim-time`.`ID` = `log-watering`.`DIMdateID`
            INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `log-watering`.`DIMsubFID`
            WHERE  `log-watering`.`isDelete` = 0 AND `dim-farm`.`dbID` IN $text1 AND `dim-time`.`Date` = '$date'
            ORDER BY   `dim-farm`.`dbID` ";
            $DATA = selectData($sql);

            $len = $DATA[0]['numrow'];
            for ($i = 1; $i <= $len; $i++) {
                $text .= "<tr>
                            <td class=\"text-left\">{$INFOFARM[$DATA[$i]['FSID']]['NameFarm']}</td>
                            <td class=\"text-left\">{$INFOFARM[$DATA[$i]['FSID']]['NamesubFarm']}</td>
                            <td class=\"text-left\">รดน้ำช่วง " . date("H.i", $DATA[$i]['StartTime']) . " - " . date("H.i", $DATA[$i]['StopTime']) . " น. ปริมาตร {$DATA[$i]['Vol']} ลิตร</td>
                        </tr>";
            }
        } else  if ($status == 'ล้างคอขวด') {
            $sql = "SELECT `dim-farm`.`dbID` AS FSID  FROM `log-activity` 
            INNER JOIN `dim-time` ON `dim-time`.`ID` = `log-activity`.`DIMdateID`
            INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `log-activity`.`DIMsubFID`
            WHERE  `log-activity`.`DBactID` = 1 AND `log-activity`.`isDelete`=0 AND `dim-farm`.`dbID` IN  $text1 AND `dim-time`.`Date` = '$date' ";
            $DATA = selectData($sql);
            $len = $DATA[0]['numrow'];
            for ($i = 1; $i <= $len; $i++) {
                $text .= "<tr>
                            <td class=\"text-left\">{$INFOFARM[$DATA[$i]['FSID']]['NameFarm']}</td>
                            <td class=\"text-left\">{$INFOFARM[$DATA[$i]['FSID']]['NamesubFarm']}</td>
                            <td class=\"text-left\">ทำกิจกรรมล้างคอขวด</td>
                        </tr>";
            }
        } else  if ($status == 'พบศัตรูพืช') {
            $sql = "SELECT `dim-farm`.`dbID` AS FSID, `dim-pest`.`Alias`,`dim-pest`.`TypeTH` FROM `log-pestalarm` 
            INNER JOIN `dim-time` ON `dim-time`.`ID` = `log-pestalarm`.`DIMdateID`
            INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `log-pestalarm`.`DIMsubFID`
            INNER JOIN `dim-pest` ON `dim-pest`.`ID` = `log-pestalarm`.`DIMpestID`
            WHERE    `log-pestalarm`.`isDelete`=0  AND `dim-farm`.`dbID` IN  $text1 AND `dim-time`.`Date` = '$date' ";
            $DATA = selectData($sql);
            $len = $DATA[0]['numrow'];
            for ($i = 1; $i <= $len; $i++) {
                $text .= "<tr>
                            <td class=\"text-left\">{$INFOFARM[$DATA[$i]['FSID']]['NameFarm']}</td>
                            <td class=\"text-left\">{$INFOFARM[$DATA[$i]['FSID']]['NamesubFarm']}</td>
                            <td class=\"text-left\">ตรวจพบ {$DATA[$i]['Alias']} ประเภท {$DATA[$i]['TypeTH']}</td>
                        </tr>";
            }
        } else  if ($status == 'ขาดน้ำ') {
            $sql = "SELECT `dim-farm`.`dbID` AS FSID , STARTDATE.`Date` AS startT , 
            IFNULL(ENDDATE.`Date`,'ปัจจุบัน') AS endT ,`fact-drying`.`Period` FROM `fact-drying` 
            INNER JOIN `dim-farm` ON `dim-farm`.`ID` = `fact-drying`.`DIMsubFID`
            INNER JOIN  `dim-time` AS STARTDATE  ON   STARTDATE.`ID` = `fact-drying`.`DIMstartDID`
            LEFT JOIN  `dim-time` AS ENDDATE  ON   ENDDATE.`ID` = `fact-drying`.`DIMstopDID`
            WHERE `dim-farm`.`dbID` IN  $text1 AND `STARTDATE`.`Date` <= '$date' AND (`ENDDATE`.`Date` > '$date' OR `ENDDATE`.`Date` IS NULL) ";
            $DATA = selectData($sql);

            $len = $DATA[0]['numrow'];
            for ($i = 1; $i <= $len; $i++) {
                $Period = $DATA[$i]['Period'];
                $startT = date("d/m/", strtotime($DATA[$i]['startT'])) . (date("Y", strtotime($DATA[$i]['startT'])) + 543);
                if ($DATA[$i]['endT'] != "ปัจจุบัน") {
                    $endT = date('d/m/', strtotime('-1 day', strtotime($DATA[$i]['endT']))) . (date('Y', strtotime('-1 day', strtotime($DATA[$i]['endT']))) + 543);
                } else {
                    $endT = "ปัจจุบัน";
                }
                if ($Period == 0) {
                    $Period = date_diff(date_create($DATA[$i]['startT']), date_create(date("Y-m-d")))->format("%a");
                }
                $text .= "<tr>
                            <td class=\"text-left\">{$INFOFARM[$DATA[$i]['FSID']]['NameFarm']}</td>
                            <td class=\"text-left\">{$INFOFARM[$DATA[$i]['FSID']]['NamesubFarm']}</td>
                            <td class=\"text-left\">ช่วงขาดน้ำ $startT - $endT  ระยะเวลา $Period วัน</td>
                        </tr>";
            }
        } else  if ($status == 'ให้ปุ๋ย') {
            $sql = "SELECT `dim-farm`.`dbID` AS FSID ,`log-fertilizer`.`Name`,`log-fertilising`.`Vol` ,IF(`log-fertilising`.`Unit`=1,'Kg','g') AS Unit FROM `log-fertilising`
            INNER JOIN `dim-farm` ON `dim-farm`.`ID` =`log-fertilising`.`DIMsubFID`
            INNER JOIN  `dim-time` ON   `dim-time`.`ID` = `log-fertilising`.`DIMdateID`
            INNER JOIN `log-fertilizer` ON `log-fertilizer`.`ID` = `log-fertilising`.`ferID`
            WHERE `log-fertilising`.`isDelete`=0 AND `dim-farm`.`dbID` IN  $text1 AND `dim-time`.`Date` = '$date' ";
            $DATA = selectData($sql);
            $len = $DATA[0]['numrow'];
            for ($i = 1; $i <= $len; $i++) {
                $text .= "<tr>
                            <td class=\"text-left\">{$INFOFARM[$DATA[$i]['FSID']]['NameFarm']}</td>
                            <td class=\"text-left\">{$INFOFARM[$DATA[$i]['FSID']]['NamesubFarm']}</td>
                            <td class=\"text-left\">ใส่ปุ๋ย {$DATA[$i]['Name']} ปริมาณ {$DATA[$i]['Vol']} {$DATA[$i]['Unit']}</td>
                        </tr>";
            }
        }

        echo $text;
    }
}
