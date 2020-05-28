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
if ($result == 'updateInfoFarm') {
    $sql = "SELECT * FROM `db-farm`";
    print_r(json_encode(select($sql)));
}
if ($result == 'updateInfoSubFarm') {
    $sql = "SELECT * FROM `db-subfarm`";
    print_r(json_encode(select($sql)));
}
if ($result == 'getYearProdect') {
    $year = $_POST['year'];
    $fsid = $_POST['fsid'];
    $sql = "SELECT `dim-time`.`Year2`,SUM(`log-harvest`.`Weight`) AS Weight 
    FROM `log-harvest` INNER JOIN `dim-time` on `log-harvest`.`DIMdateID` = `dim-time`.`ID`
     INNER JOIN `dim-farm` on `dim-farm`.`ID` = `log-harvest`.`DIMsubFID` 
     INNER JOIN `db-subfarm` on `db-subfarm`.`FSID` = `dim-farm`.`dbID` 
     WHERE`dim-farm`.`dbID` = $fsid AND (`dim-time`.`Year2` = $year OR`dim-time`.`Year2`=" . ($year - 1) . " OR `dim-time`.`Year2`=" . ($year - 2) . " ) 
    GROUP BY `dim-time`.`Year2` ORDER BY `dim-time`.`Year2` ASC";
    $data = selectAll($sql);
    echo json_encode($data);
}
if ($result == 'getMProdect') {
    $year = $_POST['year'];
    $fsid = $_POST['fsid'];
    $sql = "SELECT `dim-time`.`Month`,SUM(`log-harvest`.`Weight`) AS Weight FROM `log-harvest` 
    INNER JOIN `dim-time` on `log-harvest`.`DIMdateID` = `dim-time`.`ID` 
    INNER JOIN `dim-farm` on `dim-farm`.`ID` = `log-harvest`.`DIMsubFID` 
    WHERE `dim-farm`.`dbID` = $fsid AND `dim-time`.`Year2`=$year
    GROUP BY  `dim-time`.`Month`
    ORDER BY `dim-time`.`Year2` ASC";
    $data = selectAll($sql);
    echo json_encode($data);
}
