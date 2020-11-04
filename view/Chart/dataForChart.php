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
        case 'selectyear' :
            print_r(json_encode(getYearAgriMap()));
        break;
        case 'chart':
            $chose_label1 = $_POST['chose_label1'];
            $chose_type = $_POST['chose_type'];
            $chose_cal = $_POST['chose_cal'];
            $chose_cond = $_POST['chose_cond'];
            $PRO = $_POST['PRO'];
            // print_r("chose_label1 = ".$chose_label1);
            if($chose_type == "cutbranch" || $chose_type == "pestcontrol"){
                if($chose_type == "cutbranch"){
                    $DBactID = 1;
                    $log = "`log-activity`";
                }else if($chose_type == "pestcontrol"){
                    $DBactID = 2;
                    $log = "`log-activity`";
                }else if($chose_type == "pest"){
                    $log = "`log-pestalarm`";
                }
                $sql = "SELECT t9.".$chose_label1." AS label , ".$chose_cal."(t9.times) AS data FROM (
                    SELECT t7.SF_dbID,COUNT(t7.SF_dbID) AS times,t7.F_dbID,t7.F_name,t7.SF_name ,t7.SubDistrinct,t7.Distrinct,t7.Province,t7.dd,t7.Month,t7.Year2,t7.`dbID`AS FM_dbID,t7.`FullName` AS FM_name FROM (
                    SELECT t6.F_dbID,t6.F_name,t6.SF_dbID ,t6.SF_name ,t6.SubDistrinct,t6.Distrinct,t6.Province,t6.dd,t6.Month,t6.Year2,`dim-user`.`dbID`,`dim-user`.`FullName` FROM (
                    SELECT t5.F_dbID,t5.F_name,t5.SF_dbID ,t5.SF_name,t5.SubDistrinct,t5.Distrinct,t5.Province,`dim-time`.dd,`dim-time`.Month,`dim-time`.Year2,t5.`DIMownerID` FROM (
                    SELECT t2.F_dbID,t2.F_name,t4.dbID AS SF_dbID,t4.Name AS SF_name,t2.SubDistrinct,t2.Distrinct,t2.Province,t2.`DIMdateID`,t2.`DIMownerID`  FROM (
                    SELECT t1.dbID AS F_dbID,t1.Name AS F_name,".$log.".`DIMsubFID`,t1.SubDistrinct,t1.Distrinct,t1.Province,".$log.".`DIMdateID`,".$log.".`DIMownerID` FROM (
                    SELECT MAX(`log-farm`.`ID`) AS max_lfID ,`dim-farm`.`dbID`,`dim-farm`.`Name`,`dim-address`.`SubDistrinct`,`dim-address`.`Distrinct`,`dim-address`.`Province` FROM `log-farm`
                    JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMfarmID`
                    JOIN `dim-address` ON `dim-address`.`ID` = `log-farm`.`DIMaddrID`
                    GROUP BY `dim-farm`.`dbID`) AS t1
                    JOIN `dim-farm` ON `dim-farm`.`dbID` = t1.dbID
                    JOIN ".$log." ON  ".$log.".`DIMfarmID` =  `dim-farm`.`ID`
                    WHERE ".$log.".`isDelete` =0 AND ".$log.".`DBactID` = ".$DBactID." )AS t2
                    JOIN (SELECT MAX(`log-farm`.`ID`) AS max_lfID ,`dim-farm`.`dbID`,`dim-farm`.`Name`,`log-farm`.`DIMSubfID` FROM `log-farm`
                    JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMSubfID`
                    GROUP BY `dim-farm`.`dbID`)AS t4 
                    ON t4.DIMSubfID =  t2.DIMsubFID)AS t5
                    JOIN `dim-time` ON `dim-time`.`ID` = t5.`DIMdateID`)AS t6
                    JOIN `dim-user` ON t6.`DIMownerID` = `dim-user`.`ID`)AS t7
                    JOIN (SELECT MAX(`log-user`.`ID`) AS max_lfID ,`dim-user`.`dbID` FROM `log-user`
                    JOIN `dim-user` ON `dim-user`.`ID` = `log-user`.`DIMuserID`
                    GROUP BY `dim-user`.`dbID`) AS t8
                    ON t7.`dbID` = t8.`dbID`
                    WHERE 1 
                    GROUP BY t7.SF_dbID,t7.".$chose_label1.")AS t9
                    GROUP BY t9.".$chose_label1." ";
                // print_r($sql);
                $DATA = selectData($sql);
                // print_r($DATA);
                $DATA[0]['unit'] = "ครั้ง";
                
            }else if($chose_type == "water1"){
                $log = "`fact-watering`";
                $sql = "SELECT t9.".$chose_label1." AS label ,".$chose_cal."(t9.days) AS data FROM (
                    SELECT t7.SF_dbID,COUNT(DISTINCT(t7.Date)) AS days,t7.F_dbID,t7.F_name,t7.SF_name ,t7.SubDistrinct,t7.Distrinct,t7.Province,t7.dd,t7.Month,t7.Year2,t7.`dbID`AS FM_dbID,t7.`FullName` AS FM_name FROM (
                    SELECT t6.F_dbID,t6.F_name,t6.SF_dbID ,t6.SF_name ,t6.SubDistrinct,t6.Distrinct,t6.Province,t6.Date,t6.dd,t6.Month,t6.Year2,`dim-user`.`dbID`,`dim-user`.`FullName` FROM (
                    SELECT t5.F_dbID,t5.F_name,t5.SF_dbID ,t5.SF_name,t5.SubDistrinct,t5.Distrinct,t5.Province,`dim-time`.Date,`dim-time`.dd,`dim-time`.Month,`dim-time`.Year2,t5.`DIMownerID` FROM (
                    SELECT t2.F_dbID,t2.F_name,t4.dbID AS SF_dbID,t4.Name AS SF_name,t2.SubDistrinct,t2.Distrinct,t2.Province,t2.`DIMdateID`,t2.`DIMownerID`  FROM (
                    SELECT t1.dbID AS F_dbID,t1.Name AS F_name,".$log.".`DIMsubFID`,t1.SubDistrinct,t1.Distrinct,t1.Province,".$log.".`DIMdateID`,".$log.".`DIMownerID` FROM (
                    SELECT MAX(`log-farm`.`ID`) AS max_lfID ,`dim-farm`.`dbID`,`dim-farm`.`Name`,`dim-address`.`SubDistrinct`,`dim-address`.`Distrinct`,`dim-address`.`Province` FROM `log-farm`
                    JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMfarmID`
                    JOIN `dim-address` ON `dim-address`.`ID` = `log-farm`.`DIMaddrID`
                    GROUP BY `dim-farm`.`dbID`) AS t1
                    JOIN `dim-farm` ON `dim-farm`.`dbID` = t1.dbID
                    JOIN ".$log." ON  ".$log.".`DIMfarmID` =  `dim-farm`.`ID`)AS t2
                    JOIN (SELECT MAX(`log-farm`.`ID`) AS max_lfID ,`dim-farm`.`dbID`,`dim-farm`.`Name`,`log-farm`.`DIMSubfID` FROM `log-farm`
                    JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMSubfID`
                    GROUP BY `dim-farm`.`dbID`)AS t4 
                    ON t4.DIMSubfID =  t2.DIMsubFID)AS t5
                    JOIN `dim-time` ON `dim-time`.`ID` = t5.`DIMdateID`)AS t6
                    JOIN `dim-user` ON t6.`DIMownerID` = `dim-user`.`ID`)AS t7
                    JOIN (SELECT MAX(`log-user`.`ID`) AS max_lfID ,`dim-user`.`dbID` FROM `log-user`
                    JOIN `dim-user` ON `dim-user`.`ID` = `log-user`.`DIMuserID`
                    GROUP BY `dim-user`.`dbID`) AS t8
                    ON t7.`dbID` = t8.`dbID`
                    WHERE 1 
                    GROUP BY t7.SF_dbID,t7.".$chose_label1.")AS t9
                    GROUP BY t9.".$chose_label1." ";
                // print_r($sql);
                $DATA = selectData($sql);
                // print_r($DATA);
                $DATA[0]['unit'] = "วัน";
            }else if($chose_type == "water2"){
                $log = "`fact-drying`";
                $sql = "SELECT t10.".$chose_label1." AS label ,".$chose_cal."(t10.days) AS data FROM (
                    SELECT t9.".$chose_label1.",t9.SF_dbID,SUM(t9.days) AS days FROM (
                    SELECT t7.SF_dbID,t7.`Period` AS days,t7.F_dbID,t7.F_name,t7.SF_name ,t7.SubDistrinct,t7.Distrinct,t7.Province,t7.Date,t7.dd,t7.Month,t7.Year2,t7.`dbID`AS FM_dbID,t7.`FullName` AS FM_name FROM (
                    SELECT t6.F_dbID,t6.F_name,t6.SF_dbID ,t6.SF_name ,t6.SubDistrinct,t6.Distrinct,t6.Province,t6.Date,t6.dd,t6.Month,t6.Year2,`dim-user`.`dbID`,`dim-user`.`FullName`,t6.`Period` FROM (
                    SELECT t5.F_dbID,t5.F_name,t5.SF_dbID ,t5.SF_name,t5.SubDistrinct,t5.Distrinct,t5.Province,`dim-time`.`Date`,`dim-time`.dd,`dim-time`.Month,`dim-time`.Year2,t5.`DIMownerID`,t5.`Period` FROM (
                    SELECT t2.F_dbID,t2.F_name,t4.dbID AS SF_dbID,t4.Name AS SF_name,t2.SubDistrinct,t2.Distrinct,t2.Province,t2.`DIMstopDID`,t2.`DIMownerID`,t2.`Period`  FROM (
                    SELECT t1.dbID AS F_dbID,t1.Name AS F_name,".$log.".`DIMsubFID`,t1.SubDistrinct,t1.Distrinct,t1.Province,".$log.".`DIMstopDID`,".$log.".`DIMownerID`,".$log.".`Period` FROM (
                    SELECT MAX(`log-farm`.`ID`) AS max_lfID ,`dim-farm`.`dbID`,`dim-farm`.`Name`,`dim-address`.`SubDistrinct`,`dim-address`.`Distrinct`,`dim-address`.`Province` FROM `log-farm`
                    JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMfarmID`
                    JOIN `dim-address` ON `dim-address`.`ID` = `log-farm`.`DIMaddrID`
                    GROUP BY `dim-farm`.`dbID`) AS t1
                    JOIN `dim-farm` ON `dim-farm`.`dbID` = t1.dbID
                    JOIN ".$log." ON  ".$log.".`DIMfarmID` =  `dim-farm`.`ID`)AS t2
                    JOIN (SELECT MAX(`log-farm`.`ID`) AS max_lfID ,`dim-farm`.`dbID`,`dim-farm`.`Name`,`log-farm`.`DIMSubfID` FROM `log-farm`
                    JOIN `dim-farm` ON `dim-farm`.`ID` = `log-farm`.`DIMSubfID`
                    GROUP BY `dim-farm`.`dbID`)AS t4 
                    ON t4.DIMSubfID =  t2.DIMsubFID)AS t5
                    JOIN `dim-time` ON `dim-time`.`ID` = t5.`DIMstopDID`)AS t6
                    JOIN `dim-user` ON t6.`DIMownerID` = `dim-user`.`ID`)AS t7
                    JOIN (SELECT MAX(`log-user`.`ID`) AS max_lfID ,`dim-user`.`dbID` FROM `log-user`
                    JOIN `dim-user` ON `dim-user`.`ID` = `log-user`.`DIMuserID`
                    GROUP BY `dim-user`.`dbID`) AS t8
                    ON t7.`dbID` = t8.`dbID`
                    WHERE 1)AS t9
                    GROUP BY t9.SF_dbID,t9.".$chose_label1.") AS t10
                    GROUP BY t10.".$chose_label1."";
                // print_r($sql);
                $DATA = selectData($sql);
                // print_r($DATA);
                $DATA[0]['unit'] = "วัน";
            }         
            if($chose_label1 == "Month"){
                for($i=1;$i<=$DATA[0]['numrow'];$i++){
                    $DATA[$i]['label'] = numberToMonth($DATA[$i]['label']);
                }
            }
            
            print_r(json_encode($DATA));

        break;
    }
}
function numberToMonth($number){
    $month = ["มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม"];
    return $month[$number-1];
}
function convertToHoursMins($time, $format = '%d:%d') {
    settype($time, 'integer');
    if ($time < 1) {
        return;
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return sprintf($format, $hours, $minutes);
}    
?>
