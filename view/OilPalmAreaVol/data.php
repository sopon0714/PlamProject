<?php

require_once("../../dbConnect.php");
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
if ($result == 'getYearProdect') {
    $year = $_POST['year'];
    $fmid = $_POST['FMID'];
    $sql = "SELECT `dim-time`.`Year2`,SUM(`log-harvest`.`Weight`) AS Weight 
    FROM `log-harvest` INNER JOIN `dim-time` on `log-harvest`.`DIMdateID` = `dim-time`.`ID`
     INNER JOIN `dim-farm` on `dim-farm`.`ID` = `log-harvest`.`DIMfarmID` 
     WHERE`dim-farm`.`dbID` = $fmid AND (`dim-time`.`Year2` > $year-20  )  AND `log-harvest`.`isDelete`=0
    GROUP BY `dim-time`.`Year2` ORDER BY `dim-time`.`Year2` ASC";
    $data = selectAll($sql);
    echo json_encode($data);
}
if ($result == 'getMProdect') {
    $year = $_POST['year'];
    $fmid = $_POST['FMID'];
    $sql = "SELECT `dim-time`.`Month`,SUM(`log-harvest`.`Weight`) AS Weight FROM `log-harvest` 
    INNER JOIN `dim-time` on `log-harvest`.`DIMdateID` = `dim-time`.`ID` 
    INNER JOIN `dim-farm` on `dim-farm`.`ID` = `log-harvest`.`DIMfarmID` 
    WHERE`dim-farm`.`dbID` = $fmid AND `dim-time`.`Year2`=$year AND `log-harvest`.`isDelete`=0
    GROUP BY  `dim-time`.`Month`
    ORDER BY  `dim-time`.`Month` ASC";
    $data = selectAll($sql);
    echo json_encode($data);
}
if ($result == 'getInfoHarvest') {
    $year = $_POST['year'];
    $fmid = $_POST['FMID'];
    $sql = "SELECT SUM(`log-harvest`.`Weight`) AS Weight,SUM(`log-harvest`.`TotalPrice`) AS TotalPrice  FROM `log-harvest` 
    INNER JOIN `dim-time` on `log-harvest`.`DIMdateID` = `dim-time`.`ID` 
    INNER JOIN `dim-farm` on `dim-farm`.`ID` = `log-harvest`.`DIMfarmID` 
    WHERE`dim-farm`.`dbID` = $fmid AND `dim-time`.`Year2`=$year AND `log-harvest`.`isDelete`=0 ";
    $data = selectData($sql);
    echo json_encode($data);
}
if ($result == 'getYearFer') {
    $year = $_POST['year'];
    $fmid = $_POST['FMID'];
    $sql = "SELECT `dim-time`.`Year2`,SUM(IF(`log-fertilising`.`Unit`=2,`log-fertilising`.`Vol`/1000,`log-fertilising`.`Vol`)) AS Vol
    FROM `log-fertilising` INNER JOIN `dim-time` on `log-fertilising`.`DIMdateID` = `dim-time`.`ID`
     INNER JOIN `dim-farm` on `dim-farm`.`ID` = `log-fertilising`.`DIMfarmID` 
     WHERE`dim-farm`.`dbID` = $fmid AND (`dim-time`.`Year2` > $year-20  )  AND `log-fertilising`.`isDelete`=0
    GROUP BY `dim-time`.`Year2` ORDER BY `dim-time`.`Year2` ASC";
    $data = selectAll($sql);
    echo json_encode($data);
}
