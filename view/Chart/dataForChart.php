<?php
require_once("../../dbConnect.php");
connectDB();
session_start(); 
require_once("../../set-log-login.php");
include_once("./../../query/query.php");

if(isset($_POST['request'])){
    $request = $_POST['request'];

    switch($request){
        case 'dist' :
            $id = $_POST['id'];
            print_r(json_encode(getDistrinctInProvince($id)));

        break;
        case 'subdist' :
            $id = $_POST['id'];
            print_r(json_encode(getSubDistrinctInDistrinct($id)));
        break;
        case 'farm' :
            $id = $_POST['id'];
            $sql = "SELECT `log-farm`.`ID`, `dim-farm`.`Name`,`dim-farm`.`dbID`,`dim-address`.`dbsubDID` FROM `log-farm`
            JOIN `dim-farm` ON `log-farm`.`DIMfarmID` = `dim-farm`.`ID`
            JOIN `dim-address` ON `dim-address`.`ID` = `log-farm`.`DIMaddrID`
            WHERE `log-farm`.`ID` IN (
            SELECT MAX(`log-farm`.`ID`) as max FROM `log-farm`
            JOIN `dim-farm` ON `log-farm`.`DIMfarmID` = `dim-farm`.`ID`
            WHERE `log-farm`.`DIMSubfID` IS NULL
            GROUP BY `dim-farm`.`dbID`)
            AND `dim-address`.`dbsubDID`= '$id'";
            print_r(json_encode(selectData($sql)));

        break;
        case 'subfarm' :
            $id = $_POST['id'];
            $sql = "SELECT `log-farm`.`ID`, subfarm.`Name`,subfarm.`dbID` FROM `log-farm`
            JOIN `dim-farm` AS farm ON `log-farm`.`DIMfarmID` = farm.`ID`
             JOIN `dim-farm`AS subfarm ON `log-farm`.`DIMSubfID` = subfarm.`ID`
            WHERE `log-farm`.`ID` IN (
            SELECT MAX(`log-farm`.`ID`) as max FROM `log-farm`
            JOIN `dim-farm` ON `log-farm`.`DIMSubfID` = `dim-farm`.`ID`
            WHERE `log-farm`.`DIMSubfID` IS NOT NULL
            GROUP BY `dim-farm`.`dbID`)
            AND farm.`dbID` = '$id'";
            print_r(json_encode(selectData($sql)));

        break;
    }
}
    
?>
